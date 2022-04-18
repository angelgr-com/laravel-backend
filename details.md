# laravel-backend

## Initialize new laravel project

```
composer create-project --prefer-dist laravel/laravel:"^8.0" laravel-backend

cd laravel-backend
code .

git init
git remote add origin https://github.com/angelgr-com/laravel-backend
```

Generate .procfile for Heroku deployment:

```
web: vendor/bin/heroku-php-apache2 public/
```

Edit app / Providers / AppSericeProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('REDIRECT_HTTPS')) {
            $url->formatScheme('https://');
        }
    }
}
```



```
git add .
git commit -m "new laravel project with heroku deployment"
git push origin main
```



## Authentication with passport

```
git flow init
git flow feature start "laravel-passport"
```



### Create database

Configure database variables in .env file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lfg_web_app
DB_USERNAME=root
DB_PASSWORD=
```

Create database lfg_web_app

**Finally using heroku database**

### Install laravel passport

https://laravel.com/docs/9.x/passport

Install Laravel Passport

```
composer require laravel/passport
```

In **config/app.php** register Passport provider:

```php
'providers' =>[
 Laravel\Passport\PassportServiceProvider::class,
],
```

Generate passport encryption keys:

```bash
php artisan migrate
php artisan passport:install
# Encryption generated successfully.
# Personal access client created successfully.
# Client ID: 1
# Client secret: 
# Password grant client created successfully.
# Client ID: 2
# Client secret: 
```

At the end of .env file add client secrets:

```bash
CLIENT_1=
CLIENT_2=
```

### Passport configuration

In **App/Models/User.php** replace 'use Laravel\Sanctum\HasApiTokens;' with:

```
use Laravel\Passport\HasApiTokens;
```

Register passport routes in **App/Providers/AuthServiceProvider.php**:

- add **use Laravel\Passport\Passport;**

- uncomment $policies array 'App\Model'

  ```php
  <?php
  namespace App\Providers;
  use Laravel\Passport\Passport;
  use Illuminate\Support\Facades\Gate;
  use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
   
   
  class AuthServiceProvider extends ServiceProvider
  {
      /**
       * The policy mappings for the application.
       *
       * @var array
       */
      protected $policies = [
          'App\Model' => 'App\Policies\ModelPolicy',
      ];
   
   
      /**
       * Register any authentication / authorization services.
       *
       * @return void
       */
      public function boot()
      {
          $this->registerPolicies();
          
          Passport::routes();
      }
  }
  ```

In **config/auth.php** add api guards:

```
'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [ 
            'driver' => 'passport', 
            'provider' => 'users', 
        ], 
    ],
```

### Create APIs Route

In **routes/api.php** we need to check if the email received with the request is within our Users table.

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Laravel Passport Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'userInfo'])->middleware('auth:api');
Route::post('/forget', [AuthController::class, 'forget']);
Route::post('/reset', [AuthController::class, 'reset']);
Route::post('/reset/{pincode}', [AuthController::class, 'reset']);
```

### Create Passport Auth Controller

Create authenticacion controller:

```bash
php artisan make:controller Api/PassportAuthController
```

In **Http / Controllers / AuthController.php**

```php
<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\ForgetMail;
use App\Http\Requests\ForgetRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate request data
        $data = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'email' => 'required|string|unique:users|email|min:8|max:64',
            'password' => 'required|string|min:8|max:32|',
            'username' => 'required|string|unique:users|min:2|max:32|',
            'steamUsername' => 'string|min:2|max:32|',
            'role' => 'string|min:4|max:5|',
        ]);

        if ($data->fails()){
            return response()->json(['message' => $data->errors()->first(), 'status' => false], 400);
        }

        // If data is validated, encrypt password and store user data
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->password),
            'username' => $request->get('username'),
            'steamUsername' => $request->get('steamUsername'),
            'role' => 'user',
        ]);

        return response()->json(['message' => 'User registered successfully'], 200);
    }

    public function login(Request $request)
    {
        // Validate request data
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        // Attempt to login user with provided data
        if (auth()->attempt($data)) {
            $user = auth()->user();
            $token = $user->createToken('PassportAuth')->accessToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function userInfo()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

    public function forget(ForgetRequest $request) {
        $email = $request->email;
        
        if (User::where('email', $email)->doesntExist()) {
            return response([
                'message' => 'Invalid Email'
            ], 401);
        }
        
        // generate Random Token
        $token = rand(10, 100000);
        
        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);
            
            // Mail send to user
            Mail::to($email)->send(new ForgetMail($token));
             
            return response([
                'message' => 'Reset password email sent.'
            ], 200);
            
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()], 400);
        }
    }

    public function reset(ResetRequest $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        // Check if email and pin exist in password_resets table
        $emailcheck = DB::table('password_resets')->where('email',$email)->first();
        $pincheck = DB::table('password_resets')->where('token',$token)->first();

        // Show error if email or pin don't exist
        if(!$emailcheck) {
            return response([
                'message' => "Email not found."
            ],401);
        }
        if(!$pincheck) {
            return response([
                'message' => "Invalid pin code."
            ],401);
        }

        // If they exist, update password and delete email from password_resets table
        DB::table('users')->where('email',$email)->update(['password'=>$password]);
        DB::table('password_resets')->where('email',$email)->delete();
        
        return response([
            'message' => 'Password changed succesfully.'
        ]);
    }
}
```

In resources / views create a folder name mail and inside forget.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Forget Password</title>
</head>
<body>
Hi<br/>
To change Your Pasword <a href="https://powerful-headland-77520.herokuapp.com/reset/{{$data}}">click here</a><br/>
    Pincode : {{ $data }}
</body>
```

## Database

### rename user table to player

https://laraveldaily.com/how-to-rename-users-db-table-in-default-laravel-auth/

To do only if project is nearly finished. Too complex at this moment...

With this error:

```
RuntimeException: Personal access client not found. Please create one. in file C:\xampp\8-1\htdocs\laravel-backend\vendor\laravel\passport\src\ClientRepository.php on line 122
```

Generate keys:

```
php artisan passport:client --personal
```

### initial data

Generate the initial data (-a creates controller, migration, seeder and factory files):

```bash
php artisan make:model Game -a
# php artisan make:model User -a
php artisan make:model Party -a
php artisan make:model Message -a
```

generated file paths: 

- Controller
  - app/Http/Controllers/
- Model

  - app/Models/
- Factory
  - database/factories/
- Migration
  - database/migrations/
- Seeder
  - database/seeders/

```
git add .
git commit -m "feat: .env, create new lfg_web_app schema, controller, migration, seeder and factory files"
```

### migrations

- Relationships

  - N-N
    - zero or many **Users** may be members of zero or many **Parties**
      - Party-User
        - party_id
        - user_id

  - 1-N
    - one **Game** may have zero or many **Parties**
    - one **Party** may have zero or many **Messages**
    - one **User** may be the owner of zero or many **Parties**
    - one **User** may send zero or many **Messages**

- Entities:

  - games (<u>id</u>, title, thumbnail, url)

  - users (<u>id</u>, name, email, password, username, steamUsername, role)

  - parties (<u>id</u>, name, <u>game_id</u>, <u>user_id</u>)
    // user_id here means party owner or creator
  - messages (<u>id</u>, from, message, date, <u>party_id</u>)


Add attributes in database / migrations:

#### games

```php
public function up()
{
    Schema::create('games', function (Blueprint $table) {
        // games (id, title, thumbnail_url, url)
        $table->uuid('id')->primary();
        $table->string('title');
        $table->string('thumbnail_url');
        $table->string('url');
        $table->timestamps();
    });
}
```

#### users

```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        // users (id, name, email, password, username, steamUsername, role)
        $table->uuid('id')->primary();
        $table->string('username');
        $table->string('email');
        $table->string('steamUsername');
        $table->timestamps();
    });
}
```

#### parties

```php
public function up()
{
    Schema::create('parties', function (Blueprint $table) {
			// parties (id, name, game_id, user_id)
            // user_id here means party owner or creator
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('game_id');
            $table->uuid('user_id');
            $table->timestamps();
            
            // If we update/remove a game, related parties
            // will be updated/deleted
            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // If we remove a user, as a creator/owner,
            // his or her related parties will be deleted
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    });
}
```

#### party-user

```php
public function up()
{
    Schema::create('games', function (Blueprint $table) {
        // parties_users (id, user_id, party_id)
        $table->uuid('id')->primary();
        $table->uuid('user_id');
        $table->uuid('party_id');
        $table->timestamps();

        // If we remove a user, he or she will
        // removed from the party
        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        // If we remove a party, all users from
        // the party will be deleted
        $table->foreign('party_id')
            ->references('id')
            ->on('parties')
            ->onUpdate('cascade')
            ->onDelete('cascade');
    });
}
```

#### message

```php
public function up()
{
    Schema::create('messages', function (Blueprint $table) {
        // messages (id, from, message, date, party_id)
        $table->uuid('id')->primary();
        $table->uuid('from');
        $table->string('message');
        $table->date('date');
        $table->uuid('party_id');
        $table->timestamps();

        // If we remove a user, his or her messages
        // will be deleted
        $table->foreign('from')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        // If we update/remove a party, its related parties
        // will be deleted
        $table->foreign('party_id')
            ->references('id')
            ->on('parties')
            ->onDelete('cascade');
    });
}
```

### factories

update table factories to generate fake data:

#### GameFactory

```php
public function definition()
{
    return [
        'title'=>$this->faker->word(),
        'thumbnail_url'=>$this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true),
        'url'=>$this->faker->url()
    ];
}
```

#### MessageFactory

```php
public function definition()
{
    $partyIds = Party::all()->pluck('id')->toArray();
    $userIds = User::all()->pluck('id')->toArray();
    
    return [
        'from'=>$this->faker->randomElement($userIds),
        'message'=>$this->faker->sentence(),
        'date'=>$this->faker->dateTime(),
        'party_id'=>$this->faker->randomElement($partyIds) 
    ];
}
```

#### PartyFactory

​      // parties (id, name, game_id, user_id)

```php
public function definition()
{
    $gameIds = Game::all()->pluck('id')->toArray();
    $userIds = User::all()->pluck('id')->toArray();
    
    return [
        'name'=>$this->faker->name(),
        'game_id'=>$this->faker->randomElement($gameIds),
        'user_id'=>$this->faker->randomElement($userIds)
    ];
}
```

#### PartyUserFactory

```php
public function definition()
{
     // parties_users (id, user_id, party_id)
    // Save all table IDs to an array to get a random element later
    $gameIds = Game::all()->pluck('id')->toArray();
    $userIds = User::all()->pluck('id')->toArray();

    return [
        'name'=>$this->faker->name(),
        'game_id'=>$this->faker->randomElement($gameIds),
        'user_id'=>$this->faker->randomElement($userIds)
    ];
}
```

#### UserFactory

```php
public function definition()
{
    return [
        'username'=>$this->faker->username(),
        'email'=>$this->faker->safeEmail(),
		'steamUsername'=>$this->faker->username()
    ];
}
```

### seeders

#### GameSeeder

```php
public function run() 
{
	Game::factory()->times(10)->create();
}
```

#### MessageSeeder

```php
public function run() 
{
	Message::factory()->times(10)->create();
}
```

#### PartySeeder

```php
public function run() 
{
	Party::factory()->times(10)->create();
}
```

#### PartyUserSeeder

```php
public function run() 
{
	PartyUser::factory()->times(10)->create();
}
```

#### UserSeeder

```php
public function run() 
{
	User::factory()->times(10)->create();
}
```

#### DatabaseSeeder

*src/database/seeders/DatabaseSeeder.php*

```php
public function run()
{
    $this->call(
        [
            GameSeeder::class,
            MessageSeeder::class,
            PartyUserSeeder::class,
            PartySeeder::class,
            UserSeeder::class
        ]
    ); 
}
```

#### Run seeders

```bash
php artisan migrate --seed

php artisan db:seed --class=UserSeeder
php artisan db:seed --class=GameSeeder
php artisan db:seed --class=OwnerSeeder
php artisan db:seed --class=PartySeeder
php artisan db:seed --class=MessageSeeder
php artisan db:seed --class=PartyUserSeeder

php artisan migrate --path=./database/migrations/2022_04_17_160913_create_party__users_table.php

php artisan db:seed --class=MessageSeeder
php artisan db:seed --class=PartyUserSeeder

php artisan cache:clear
php artisan config:clear
php artisan route:clear
composer dumpautoload
composer update

php artisan migrate:fresh --seed
```

## API Routes

### Endpoints

```php
```

### User CRUD

#### register

#### login

#### userInfo

#### forget

#### reset

### Game CRUD

#### getGames

#### getGame

#### newGame

#### editGame

#### deleteGame

### Party CRUD

#### getParties

#### getParty

#### newParty

#### editParty

#### deleteParty

### Message CRUD

#### getMessages

#### getMessage

#### newMessage

#### editMessage

#### deleteMessage

## Credits

Repository:

- [How to Use the Repository Pattern in a Laravel Application [twilio.com]](https://www.twilio.com/blog/repository-pattern-in-laravel-application)

Migration, seeder and factories:

- [How can I maintain foreign keys when seeding database with Faker? [stackoverflow.com]](https://stackoverflow.com/a/42038737)

- https://fakerphp.github.io/

- [How to Add a New Column to an Existing Table in a Laravel Migration? [devdojo.com]](https://devdojo.com/bobbyiliev/how-to-add-a-new-column-to-an-existing-table-in-a-laravel-migration)

Authentication:

- https://www.tutsmake.com/laravel-8-rest-api-authentication-with-passport/

- https://blog.logrocket.com/laravel-passport-a-tutorial-and-example-build/

- Refactor register function: https://stackoverflow.com/questions/62692169/laravel-passport-register-wrong-response

- Using UUIDs https://www.larashout.com/using-uuids-in-laravel-models