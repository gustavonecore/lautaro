Smart routing
=============================
Leftaro use smart-routing depending off the given url following the next rules:

- Words separated by `-` indicate n* capitalized words.
e.g: `/user-profile-requests` will be linked to the `UserProfileRequestsController`
- Nested routes are wired to the folder definition, like:
`/user/120/profile` will force the routing engine to search inside the namespace defined as `Controllers/User/ProfileController` and also, the routing will follow this folder convention as well.