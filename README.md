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

## Usage

Bricolo provides several command-line commands. The `serve` command is one of the main functionalities, enabling quick and flexible server setup directly from the CLI.

### Viewing Help Information

To see a list of available commands and get a quick overview of each, you can run:

```bash
php bricolo help
```

This will display descriptions for each Bricolo command, including `serve` and `migrate`, along with usage examples to help you get started quickly.

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

The `migrate` command enables you to initialize and set up your database with tables and default data.

#### Step 1: Configure Database Connection in `.env`

To use the database functionality, create a `.env` file in the root directory with the following variables:

```plaintext
D_HOST=127.0.0.1
D_NAME=your_database_name
D_USER=your_username
D_PWD=your_password
```

#### Step 2: Create an SQL Dump File

Bricolo will look for a default SQL dump file at `src/Data/dump_sql.txt` to create tables and insert data. An example SQL dump file might look like this:

```sql
-- src/Data/dump_sql.txt
CREATE TABLE `users` (
    `userId` int(3) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`userId`, `password`) VALUES
(:userId, :password);
```

Feel free to customize this file to set up your initial tables and data.

#### Step 3: Run the Migration Command

To create the database and set up tables from the SQL dump file, use the `migrate` command:

```bash
php bricolo migrate
```

**Migration Process**

1. **Check for Database Existence**: The `migrate` command will first check if the specified database exists. If it does not exist, it will create it.

2. **Run SQL Dump**: After confirming the database, Bricolo will execute the SQL commands in `src/Data/dump_sql.txt`, creating tables and inserting data as specified.

This automates the setup process, making it quick and efficient to prepare a fresh database environment for development.

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
php bricolo migrate   # Run database migration to set up tables
php bricolo serve     # Start a local server on the default or available port
```

If the migration succeeds, you’ll see a message confirming that the database and tables were created.

## Contributing

We welcome contributions! Here’s how to contribute:

- **Fork** the project.
- **Create your feature branch**: `git checkout -b features/Myfeature`.
- **Commit your changes**: `git commit -m "✨ Introducing Myfeature!"`.
- **Push to Github**: `git push origin features/Myfeature`.
- **Open a Pull Request**.

<!-- CONTACT -->

## Contact

If you have any questions, feel free to reach out:

Antoine Bollinger - [LinkedIn](https://www.linkedin.com/in/antoinebollinger/) - [antoine.bollinger@gmail.com](mailto:antoine.bollinger@gmail.com)

You can talk to me in 🇫🇷, 🇧🇷 or 🇬🇧.