
// --------- For Developers --------- //
Things to be fixed:
    * I implemented the Client Information box on the add project page entirely
    with custom CSS. However I have been told that it should be possible to get
    that type of formating using Moodle Forms API and at some point it should be
    implemented in this way.

Important Note:
    If linking to SugarCRM isn't working then you may want to first test
your error reporting status. It seems sugar wrote code that isn't compliant with 
the strict standards so every page will have at least one "Strict Standards: 
Non-static method ACLController" error.

    These errors break the soap parsing library because it doesn't know how to
deal with an extra <br><strong> ... at the top of an xml file.