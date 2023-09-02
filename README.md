# Pandora's Box

Author: Daniel Gilbert (AdaptOrDie247)

A honeypot written in PHP that hacks back.

## Disclaimer

This project is for security research only. Don't break the law.

## WARNING

If you make this application public-facing, make sure you know what you're doing.

Insecure deployment of the application can result in a system compromise, or worse.

## Quick Setup

1. Install system dependencies: PHP, SQLite3.
2. Git clone the repo.
3. Install and run Composer to install the project dependencies.
4. Create a `.env` file in the project root with the following values:
- `time_zone`: The time zone you want to log requests in. E.g., `America/Los_Angeles`.
- `database.directory`: The database directory. E.g., `writable/database`.
- `database.name`: The database name. E.g., `pandoras_box.db`.
5. Open a terminal in the `public` directory.
6. Start the PHP web server: `php -S localhost:8000`.
7. Navigate to `localhost:8000` in a web browser.
8. Check the SQLite3 database for the HTTP request info.

## Notes

Currently this project just logs web traffic. Obviously, this can pile up quick.

My plan is to log unique visitors using a fingerprint (IP, user agent).

Once better logging is in place (database), will consider gathering client intel.
- IP location
- IP ownership
- IP reputation
- Reverse DNS
- DNS whois

Will plant fake sensitive files in common web fuzzing directories.

Upon client retrieving such files...
- Nmap scan remote host
- Unleash Pandora's Box on the remote host
