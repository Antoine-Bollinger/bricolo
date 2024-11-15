# Bricolo

**Bricolo** is a PHP command-line utility library that provides tools for managing databases, running servers, and enhancing PHP framework functionality. With features like dynamic port assignment and color-coded output, Bricolo is designed to simplify and automate server setup, database management, and terminal output styling.

## Features
- **CLI Server Management**: Easily launch a local PHP server, with dynamic port adjustment if a port is occupied.
- **Database Management**: Run queries and manage database connections from the command line.
- **Color-Coded Output**: Add color to terminal outputs for enhanced readability.
- **Framework Agnostic**: Designed to work with any PHP environment or as a standalone utility.

<details open="open">
  <summary><b>Table of Contents</b></summary>
  <ol>
    <li><a href="#installation">Installation</a></li>
    <li><a href="#usage">Usage</a>
        <ul>
            <li><a href="#viewing-help-information">Viewing Help Information</a></li>
            <li><a href="#launching-a-local-server">Launching a Local Server</a></li>
            <li><a href="#database-management">Database Management</a></li>
            <li><a href="#page-creation">Page Creation</a></li>
            <li><a href="#color-coded-cli-output">Color-Coded CLI Output</a></li>
        </ul>
    </li>
    <li><a href="#example-command">Example Command</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

## Installation

Install the last version via [Composer](https://getcomposer.org/):

```bash
composer require abollinger/bricolo
```

Then you can call the functions by typing:

```bash 
php vendor/bin/bricolo functionName
```

**Note**

We strongly recommand to add a file named `bricolo` (without extension) at the route of your project, with the following content:

```php
#!/usr/bin/env php
<?php
passthru(
    "php vendor/bin/bricolo " . implode(' ', array_map(
        'escapeshellarg', 
        array_slice($_SERVER['argv'], 1)
    )),
    $exitCode
);
exit($exitCode);
```

You can use this command to automatically create this file:

```bash
php vendor/bin/bricolo createLauncher
```

This will allow you to type `php bricolo functionName` instead of `vendor/bin/bricolo`. For the rest of this documentation, we'll use the `php bricolo functionName` format.


## Usage

Bricolo provides several command-line commands. The `serve` command is one of the main functionalities, enabling quick and flexible server setup directly from the CLI.


### Viewing Help Information

To see a list of available commands and get a quick overview of each, you can run:

```bash
php bricolo help
```


### Launching a Local Server

The `serve` command launches a PHP server on a specified port and host. If the chosen port is already in use, Bricolo will automatically increment the port number until an available one is found.

```bash
php bricolo serve p=port h=host d=directory
```

- `p` (optional) - Port number. Defaults to `1234`.
- `h` (optional) - Host address. Defaults to `localhost`.
- `d` (optional) - Document root directory. Defaults to the current directory (`""`).

#### Example of usage

1. Start a Server on the Default Port and Host:

```bash
php bricolo serve
```

This will attempt to start a server at `localhost:1234` with the current directory as the root. If port `1234` is in use, it will try `1235`, then `1236`, and so on until it finds an open port.

2. Specify a Custom Port and Host:

```bash
php bricolo serve p=8080 h=127.0.0.1 d=/path/to/your/project
```

- Host: `127.0.0.1`
- Port: `8080` (or the next available port if `8080` is occupied)
- Directory: `/path/to/your/project`

The server will start on the specified host and port, using `/path/to/your/project` as the document root.


### Database Management

The `migrate` command in Bricolo enables you to initialize and set up your database with tables and default data. This command automates the creation of essential tables and can insert sample data to get your database up and running quickly.

#### Step 1: Configure Database Connection in `.env`

To enable database functionality, set up a `.env` file in the root directory of the Partez framework with the following required database connection variables:

```plaintext
D_HOST=127.0.0.1
D_NAME=your_database_name
D_USER=your_username
D_PWD=your_password
```

#### Step 2: Customize the SQL Dump File (Optional)

Bricolo uses a default SQL dump file located at `src/Data/templates/populate.sql` to create tables and insert data. By default, this file includes SQL commands to create a basic users table:

```sql
-- src/Data/templates/populate.sql
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `userId` int(3) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`userId`, `password`) VALUES
('999', '$2y$12$pSe7DWznXBHgvAZ9AtvJl.OxFhmX9694wFTmEJL5kKFsrmsb5uzzu');
```

The password used here a `1234` ecnrypt with the password_hash() function, following this model:

```php 
$encryptedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => $_ENV["SALT"]]);
```

We strongly recommend to customize this method in your own app.

**Providing a Custom SQL Dump File**

If you’d like to use a different SQL dump file, specify its path in the `.env` file with the optional variable `APP_DUMP_SQL`. For example:

```plaintext
APP_DUMP_SQL=custom_dump.sql
```

When `APP_DUMP_SQL` is set and the specified file exists, Bricolo will use it instead of `src/Data/templates/populate.sql`. This feature gives you the flexibility to customize database setup while keeping a default structure available.

**Note: Ensure that the path specified in `APP_DUMP_SQL` is relative to the root of the Partez framework or provide an absolute path.**

#### Step 3: Run the Migration Command

To initialize the database and set up tables as defined in the SQL dump file, run the `migrate` command:

```bash
php bricolo migrate
```

#### Migration Process

When you run the migrate command, Bricolo will follow these steps:

1. **Verify Database Connection**: Bricolo will check if it can connect to the specified database server using the credentials from `.env`. If the database specified in `DB_NAME` does not already exist, it will be created.

2. **Locate SQL Dump File**: Bricolo will first check for a custom SQL dump file path in `APP_DUMP_SQL`. If this variable is set and the file exists, it will be used for the migration. Otherwise, Bricolo will fall back to the default `src/Data/templates/populate.sql`.

3. **Execute SQL Commands**: Bricolo will read the SQL commands from the selected dump file and execute them in sequence to set up tables and insert any predefined data.

This automated setup process simplifies initializing a new database environment, making it efficient to prepare a clean, ready-to-use database for development.

#### Example `.env` file

```plaintext
DB_HOST=127.0.0.1
DB_NAME=my_database
DB_USER=my_user
DB_PASSWORD=my_password
APP_DUMP_SQL=custom_migration.sql
```


### Page Creation

Bricolo provides a straightforward way to generate a new controller and view for a page. This functionality is accessed from the command line and can quickly create files based on your predefined templates.

#### Using `createPage` from the Command Line

The `createPage` command generates:

1. A Controller file based on a controller template.
2. A View file in `.twig` format, using a view template.

**Command Syntax**

To create a new page, use the following command:

```bash
php bricolo createPage name="PageName" route="/page-route"
```

**Parameters**

- `name`: The name of the page to create. This is required and will be formatted automatically, with the first letter capitalized (e.g., `"TestPage"`).
- `route`: The route for the page (e.g., `"/test-page"`). This route will be injected into the template wherever {{ route }} appears.

**File Locations**

- **Controllers**: By default, controllers are created in the directory defined by the `APP_CONTROLLERS` variable defined in `.env`. If `APP_CONTROLLERS` is not defined, Bricolo will use the root of the project.

- **Views**: Views are created in the directory specified by the `APP_VIEWS` variable defined in `.env`. If `APP_VIEWS` is not defined, Bricolo will use the root of the project.

**Error Handling**

If either name or route is missing, createPage will display an error message. Any other errors will also be displayed for troubleshooting.


### Color-Coded CLI Output

Bricolo includes a `sprintc` function to apply color formatting to CLI output, making it easier to differentiate messages by their type (e.g., errors in red, success messages in green).

```php 
use Abollinger\Bricolo\Data\Constants;

echo sprintc("Migration completed successfully!", Constants::COLOR_GREEN);
echo sprintc("Error: Database connection failed.", Constants::COLOR_RED);
```


## Example Command

Below is an example sequence to set up and launch a development environment with Bricolo:

```bash
php bricolo migrate                                  # Run database migration to set up tables
php bricolo createPage name="Page" route="/page"     # Create a new Controller named PageController and a twig view name PageView.twig 
php bricolo serve                                    # Start a local server on the default or available port
```


## Contributing

We welcome contributions! Here’s how to contribute:

- **Fork** the project.
- **Create your feature branch**: `git checkout -b features/Myfeature`.
- **Commit your changes**: `git commit -m "✨ Introducing Myfeature!"`.
- **Push to Github**: `git push origin features/Myfeature`.
- **Open a Pull Request**.


## Contact

If you have any questions, feel free to reach out:

Antoine Bollinger - [LinkedIn](https://www.linkedin.com/in/antoinebollinger/) - [antoine.bollinger@gmail.com](mailto:antoine.bollinger@gmail.com)

You can talk to me in 🇫🇷, 🇧🇷 or 🇬🇧.