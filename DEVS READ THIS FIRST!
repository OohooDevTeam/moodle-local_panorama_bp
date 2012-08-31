
// --------- For Developers --------- //
Important Note:
    If linking to SugarCRM isn't working then you may want to first test
your error reporting status. It seems sugar wrote code that isn't compliant with 
the strict standards so every page will have at least one "Strict Standards: 
Non-static method ACLController" error.

    These errors break the soap parsing library because it doesn't know how to
deal with an extra <br><strong> ... at the top of an xml file.


//***************//
The process for filling out a new project is as follows.

Basic process for this plugin is as follows:
(The system will log each of these steps).

    1. User Clicks the Add Project Button
    2. User fills out basic client information form. (add_project_s1_form.php) //Stage 1 form.
    3. User fills out a quote for the client.   (add_project_s2_form.php) //Stage 2 form.
    4. User marks quote as complete and saves. (add_project_s2_form.php) //Stage 2 form.
    5. System sends copy of quote to client and admin. (no page exists yet)
    6. System creates a pdf copy of the quote and saves it as a file. (no page exists yet)
    7. Client either accepts or rejects the quote (add_project_s2_form.php) //Stage 2 -- Limited abilities to change things based on permissions.
    8. If accepted goto: 8a, if rejected goto 8b.

    8a. System sends emails to admin, ceo, cto and sales department. 
    9. User creates a service agreement and uploads it to System. (add_project_s3_form.php)//Stage 3
    10. User marks as complete and saves.(add_project_s3_form.php)//Stage 3
    11. System sends the agreement to the client.
    12. ... Rest is still in progress.

    8b. System sends email to sales.
    9. END

The plugin will need the following roles:
    1. admin
    2. ceo
    3. cto
    4. sales
    5. client
    6. lead programmer
    7. programmer