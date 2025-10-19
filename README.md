# Notes Portal with Uploads & Admin Approval

## Setup
1. Copy repo to PHP server (XAMPP, LAMP, etc).
2. Ensure writable directories: notes/, pending_uploads/, data/
3. Default admin: admin / admin123 — change immediately.
   - To change, login as admin and update data/users.json or replace hash.
4. Users can register and login to upload PDFs.
5. Admin approves uploads from Admin Panel; approved files move to notes/<BRANCH>/<YEAR>/<SEM>.
6. Ratings are stored in data/ratings.json.

## Security notes
- This is a minimal system for demo and school use.
- For production: use HTTPS, CSRF protections, input validation, change admin password, store files outside docroot if possible, and consider a database.
