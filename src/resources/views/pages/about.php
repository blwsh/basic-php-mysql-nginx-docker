<content title="About" template="layout.default">
    <div class="container">
        <div class="content">
            <h1 class="title title--divider">About</h1>

            <p>This page details how this application works, it's structure and security measures in place.</p>

            <h2 id="structure"><a href="#structure">#</a> Structure</h2>

            <div class="indent">
                <ul class="list">
                    <li>
                        <strong><a href="#app" title="Framework\App">App</a></strong> - Contains mostly business logic.
                        <ul class="list">
                            <li><strong>Models</strong> - Models provide a connection to a database and relation between database data and PHP objects.</li>
                            <li><strong>Controllers</strong> - Controllers are where most of the business logic is executed. Controller should return instances of <code>Framework\View</code> or data which is automatically converted to json.</li>
                            <li><strong>Commands</strong> - Contains classes which can be executed via CLI.</li>
                            <li><strong>Classes</strong> - Can contain business logic. Unlike controllers, data/views don't have to be returned.</li>
                        </ul>
                    </li>
                    <li><strong><a href="#autoloader">Autoloader</a></strong> - Contains one PHP file which is responsible for including classes based on namespace.</li>
                    <li><strong><a href="#framework">Framework</a></strong> - Contains framework classes such as Model, Controller and View. These files should not be modified often.</li>
                    <li>
                        <strong><a href="#public">Public</a></strong> - The only web accessible directory. Contains one index.php file which handles all requests regardless of path.
                        <ul class="list">
                            <li><strong>Assets</strong> - Contains assets such as css, js and images.</li>
                        </ul>
                    </li>
                    <li>
                        <strong><a href="#resources">Resources</a></strong> - Contains views and resources.
                        <ul class="list">
                            <li><strong><a href="#scss">Scss</a></strong> - Contains scss files. These files are combined and compiled in a css files stored in public/assets/app.css</li>
                            <li><strong><a href="#views">Views</a></strong> - Contains views, files which are handled by view engine and returned by controllers.</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <h2 id="routing"><a href="#routing">#</a> Routing, responses and file/folder access</h2>

            <div class="indent">
                <p>As mentioned in <a href="#security">the security section</a>, the key used for the openssl function is stored in <code>config.php</code> however, if you try to access this file, you will receive a 404 error page. This is because the server has been configured through the use of a <code>.htaccess</code> file to only serve files that are in the public directory.</p>
                <p>This may seem confusing because if you look in the public directory there is only one PHP  file (<code>index.php</code>). There is no /about directory or /films directory. Instead, all requests to the server are redirected to the index.php file which then determines what controller method to call based on the path you requested. A controller may return an instance of <code>Framework\View</code> or any other form of data which is then automatically converted to json by the <code>Framework\Dispatcher</code></p>
                <p>The logic used to determine which controller you should be sent to can be found in <code>Framework\Router</code>.</p>
                <p>Route definitions can be found in <code>app/routes.php</code> and may look something likes this:</p>
                <?= view('components.code', [highlight_string("<?php \nRouter::get('/about', 'AboutController@about');\nRouter::post('/login', 'AuthController@loginRequest');", true)]); ?>
                <p>If we we destruct one of the functions above it's fairly easy to understand what's going.</p>
                <p><code>Router::get()</code> and <code>Router::post()</code> are both static methods which are used to register what controller methods should be called when a particular path is visited.</p>
                <p>So for example, <code>Router::get('/about', 'AboutController@about');</code> would call the <strong>about</strong> method of the <strong>AboutController</strong> when the url <strong>/about</strong> is requested.</p>
                <p>There are four static router methods you can call to register routes:</p>
                <ul class="list">
                    <li><code>get</code></li>
                    <li><code>post</code></li>
                    <li><code>put</code></li>
                    <li><code>delete</code></li>
                </ul>
                <p>Each of these methods register a route for a particular HTTP method. This mean if your browser makes a get request to the route /about which you can see defined in the example above, the application will call AboutController@about but if a post request is made to the same path you will receive a 404 error.</p>
            </div>

            <h2 id="app"><a href="#app">#</a> Framework\App</h2>

            <div class="indent">
                <p>The App class uses the <a href="https://en.wikipedia.org/wiki/Singleton_pattern" target="_blank">singleton design pattern</a>. This means we can attach things to a single, globally available instance of the App class, thus eliminating the need to reinstate objects such as the database connection.</p>
                <p>To access the App instance form anywhere in the application you either of the following methods will work:</p>
                <?= view('components.code', [highlight_string("<?php \napp(); // Get the app via global helper \nApp::getInstance(); // Get the app via global helper", true)]) ?>
                <p>The App class has three very important private properties:</p>
                <div class="indent">
                    <h3>$connection <code>Framework\Connection</code></h3>
                    <p>This class constructs an instance of PDO which is then used everywhere in the app.</p>
                    <h3>$router <code>Framework\Router</code></h3>
                    <p>This is an instance of the application router (<code>Framework\Router</code>) It's responsibility is to determine what route a client has requested and return it.</p>
                    <h3>$dispatcher <code>Framework\Dispatcher</code></h3>
                    <p>The dispatcher's responsibility is to dispatch responses based on the Route passed to it.</p>
                </div>
            </div>

            <h2 id="autoloader"><a href="#autoloader">#</a> Autoloader</h2>

            <div class="indent">
                <p>As the name suggests the autoloaders responsibility is to load classes used by the application.</p>
                <p>To do this the PHP function spl_autoload_register is used and works based on directory structure and class paths.</p>
                <p>The autoloader also includes two files which do not have classpaths; <code>helpers.php</code> and <code><a href="#routing">routes.php</a></code></p>
            </div>

            <h2 id="framework"><a href="#framework">#</a> Framework</h2>

            <div class="indent">
                <p>The framework directory contains classes such as <code title="Framework\QueryBuilder">QueryBuilder</code>, <code title="Framework\Model">Model</code>, <code title="Framework\Connection">Connection</code> and <code title="Framework\Controller">Controller</code></p>
                <p>Most of these classes are extended, instantiated and/or statically called from code found in the app folder.</p>
            </div>

            <h2 id="public"><a href="#public">#</a> Public</h2>

            <div class="indent">
                <p>All folders excluding public and it's subdirectories are inaccessible which is why there is only an index.php file and assets in this folder.</p>
                <p>By making only the <code>/public</code> folder accessible we don't have to worry about private files such as the config.php file being read/executed.</p>
                <p>When linking to assets in the public folder the <code>/public/</code> path should be omitted because the <code>.htaccess</code> file redirects all request to <code>/public</code> automatically.</p>
            </div>

            <h2 id="resources"><a href="#resources">#</a> Resources</h2>

            <div class="indent">
                <p>The resources folder should contain files which may need to be complied, built or served.</p>

                <h3 id="scss">Scss</h3>
                <p>The scss folder contains scss files which are all compiled in to one file and saved in <code>public/assets/css</code>. You can learn more about scss <a href="https://sass-lang.com/" target="_blank">here</a>.</p>

                <h3 id="views">Views</h3>
                <p>The view folder contains php files which can be rendered using the <code>view</code> helper or by instantiating a new instance of <code>Framework\View</code>.</p>
                <p>Views can be returned by controllers and used within views.</p>
                <p>When using the view helper or instantiating a new view it is important to remember the path you provide should be in dot notation and assume that <code>resources/views</code> is the root.</p>
                <p>In some views you may see the following code at the top of the page:</p>
                <?= view('components.code', [highlight_string("<?php \n<content title=\"About\" template=\"layout.default\">", true)]) ?>
                <p>This tag is recognised as a block that contains content which should be inserted in to a parent layout. The parent layout is specified using the template attribute. Everything between the opening and closing content tag will be inserted.
            </div>

            <h2 id="security"><a href="#security">#</a> Security</h2>

            <div class="indent">
                <h3>Passwords</h3>
                <p>Your password is encrypted using the bcrypt hashing algorithm using the password_hash method. This means if the database is ever compromised all customers passwords are unrecognisable and can not be used to login via the login page.</p>
                <p>The bcrypt hashing algorithm is a one way form of encryption. This means the password can not be decrypted. To check a password we use the <code>password_verify</code> function. A password may be retrieved from session or sent to the server. For the registration page and login page, a <code>POST</code> request is used so the data does not appear in the browser search bar suggestions or browser history.</p>
            </div>

            <div class="indent">
                <h3>Tokens</h3>
                <p>To store tokens we use PHP sessions. A token for our application is made up of two parts; The email and the encrypted password.</p>
                <p>While the password is already encrypted therefore, useless to anybody who obtains it, an extra security measure is taken to ensure the email is not revealed nor the encrypted password if php session data is somehow compromised.</p>
                <p>This is done through the use of the function <code>openssl_encrypt</code> and <code>openssl_decrypt</code></p>
                <p>The opensll function encrypts strings using a two way encryption algorithm meaning the original string can be retired but only if you have a valid key.</p>
                <p>The parameters used for the openssl_encrypt and openssl_decrypt functions are:</p>
                <ul class="list">
                    <li>The password</li>
                    <li>The hashing algorithm used to encrypt it</li>
                    <li>And the key that was used to encrypt it</li>
                </ul>
                <p>The key use for encrypting and decrypting openssl strings is stored in <code>config.php</code> and never leaves the server.</p>
            </div>

            <h2 id="debugging"><a href="#debugging">#</a> Debugging</h2>

            <div class="indent">
                <p>Because all responses are served by the <code title="App\Dispatcher">Dispatcher</code> class we can catch all exceptions thrown by the app and return an appropriate response.</p>
                <p>To enable debugging you can either enable it in the <code>config.php</code> file or by visiting <a href="<?= url('/debug') ?>">/debug</a>.</p>
                <p>If debugging is enabled, the the app will return a detailed error page otherwise, a 500 error page will show.</p>
            </div>
        </div>
    </div>
</content>