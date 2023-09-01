# Pandora's Box

Author: Daniel Gilbert (AdaptOrDie247)

A honeypot written in PHP that hacks back.

## Disclaimer

This project is for security research only. Don't break the law.

## WARNING

If you make this application public-facing, make sure you know what you're doing.

Insecure deployment of the application can result in a system compromise, or worse.

## Quick Setup

1. Clone the repo.
2. Open a terminal in the `public` folder.
3. Run the following command: `php -S localhost:8000`.
4. Navigate to `localhost:8000` in your web browser.
5. Check the `writable/logs` directory for an output file.

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
