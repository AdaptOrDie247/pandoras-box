# The Watcher

Author: Daniel Gilbert (AdaptOrDie247)

A honeypot that performs passive recon on the remote host.

## WARNING

If you make this application public-facing, make sure you know what you're doing.

Insecure deployment of the application can result in a system compromise, or worse.

## Quick Setup

1. Install system dependencies: PHP, SQLite3.
2. Git clone the repo.
3. Install and run Composer to install the project dependencies.
4. Create a `.env` file in the project root with the following values:
- `database.directory`: The database directory. E.g., `writable/database`.
- `database.name`: The database name. E.g., `the_watcher.db`.
5. Open a terminal in the `public` directory.
6. Start the PHP web server: `php -S localhost:8000`.
7. Navigate to `localhost:8000` in a web browser.
8. Check the SQLite3 database for the relevant information.
