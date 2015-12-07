# SlateCrate
SlateCrate is designed to allow students and faculty members of RPI to share relevant information pertaining to RPI courses. Members can share links that pertain to different courses, and vote to show support, or opposition, of links posted. 

## Installation
1. Ensure Apache and MySQL are installed and are running
2. Clone this GitHub repository into a local directory
3. Copy the files from the repository into the server’s root directory
4. Ensure `resources/config.php` matches your database’s login information
5. Execute the schema’s installation script by running the SQL code found at `schema.sql` on your database.
6. *Optional:* Execute the SQL code found at `sampledata.sql` for a starter set of data, obtained from the Spring 2016 listing found on the RPI Student Information Service.

## Dependencies
The following libraries have been included with SlateCrate. With the exception of jQuery, static files, as opposed to content distribution networks, were used because the versions included with the project have been tested and confirmed to work with the current implementation of SlateCrate.

### Languages
SlateCrate makes use of Hypertext Markup Language 5 (HTML 5), Cascading Style Sheets 3 (CSS3), JavaScript, and PHP Hypertext Preprocessor (PHP).

### JavaScript Dependencies
* [jQuery - v1.11.0](http://jquery.com) -  this dependency is used for form validation and for functions provided with Bootstrap (see next item).
* [Bootstrap - v3.1.1](http://getbootstrap.com) - this dependency is used for tooltips on the search fields, but has extentions that can benefit SlateCrate in the future. Note: this dependency is coupled with a CSS dependency of the same name.
* [Selectize - v0.12.1](brianreavis.github.io/selectize.js) - converts select elements, that are targeted by jQuery, to allow for a hybrid of select and input, with searching enabled.
* [HTML5 Shiv - v3.7.0](https://github.com/afarkas/html5shiv) - for providing backwards compatibility to Internet Explorer 6-9, Safari 4, iPhone 3, and Firefox 3.
* [Respond.js - v1.4.2](https://github.com/scottjehl/Respond) - for providing backwards compatibility to Internet Explorer versions 6-8.

### CSS Dependencies
* [Bootstrap - v3.1.1](http://getbootstrap.com) - this dependency is used for the majority of layout used in SlateCrate. Note: this dependency is coupled with a JavaScript dependency of the same name.
* [Selectize - v0.12.1](brianreavis.github.io/selectize.js)  - provides a Bootstrap-esque style for the Selectize hybrid element, defined above. Note: this dependency is coupled with a JavaScript dependency of the same name.
* [Font Awesome - v4.4.0](http://fontawesome.io) - provides a library of vector, font-based icons that can be used by including specific CSS classes on an empty span element.

### PHP Dependencies
* [phpCAS - v1.3.4](https://wiki.jasig.org/display/CASC/phpCAS) - used to implement RPI’s single sign-on, central authentication service.

## Structure
The SlateCrate codebase is divided into four major portions: `root`, `assets`, `resources`, and `partials`. The `root` contains all pages that the user is intended to view and access. The `assets` directory contains all static imagery, Cascading Style Sheets, and JavaScript documents that are consumed by the application.

The `resources` directory contains PHP code that is not intended to be viewed directly by the user, but consists of functions and code that is used by views to provide cleaner code across the project. Finally, the `partials` directory contains reusable ‘chunks’ of HTML code (sometimes accompanied by PHP code) to prevent code duplication across the project.

### Sitemap
* Home (index.php)
* Classes (classes.php)
  * Add Class (addclass.php)
  * Edit Class (editclass.php)
* Links (links.php)
  * Links filtered for a class (links.php?class=<class id>)
  * Add Class (addlink.php)
  * Edit Class (editlink.php)
* Login (login.php)
* Logout (logout.php)

### Code Documentation Standards

#### PHP Documentation
All PHP functions, classes, and significant portions of code are expected to provide a  docstring, a comment that provides a high-level explanation of the function, along with descriptions of parameters and return type for functions. These docstrings should follow the [PHPDoc standard](https://en.wikipedia.org/wiki/PHPDoc). Additionally, comments are included periodically within the code to explain the purpose of more dense lines of code.

#### JavaScript Documentation
All JavaScript functions, classes, and significant portions of code are expected to provide a  docstring, a comment that provides a high-level explanation of the function, along with descriptions of parameters and return type for functions. These docstrings should follow the [JSDoc 3 standard](http://usejsdoc.org/).

#### Other Documentation
HTML and CSS should include comments that explain the purpose of code that may be unclear.

## Links
Links are stored in a single SQL table with a category identifier, which references the id in the class table. The identifier listed in the get request on the links page, stored as a variable called class. If this is not set, the user can view all of the links on the site without narrowing it down by a specific class. The links are given a name and an initial score of 0, though the score can be edited by any user using the upvote or downvote function. This function should be edited in the future so that the user can contribute one point to the score.

### SQL Structure
Links are stored in the `links` table of the `slatecrate` database. The `links` table contains the following fields:

* ``` `link_id` ``` - integer - primary key
* ``` `link` ``` - varchar of size 2000
* ``` `rcs_id` ``` - varchar of size 20
* ``` `category_id` ``` - integer
* ``` `creation_date` ``` - date
* ``` `title` ``` - varchar of size 200
* ``` `score` ``` - integer

## Classes
In the context of SlateCrate, classes refer to the means of categorization for the project. Links are assigned a category identification number, which associates them with a class. The classes are given an identification number which is passed through a get request to the links page as was referred to in the links section. The classes are stored in a separate table from the links, each row containing the id of the person who created it, the date, and the prefix.

### SQL Structure
Classes are stored in the `categories` table of the `slatecrate` database. In the database, any reference to `category` refers to classes. This vagueness is intentional, as SlateCrate was implemented with extensibility in mind. The `categories` table contains the following fields:
* ``` `category_id` ``` - integer - primary key
* ``` `title` ``` - varchar of size 50
* ``` `links` ``` - integer
* ``` `prefix` ``` - varchar of size 4
* ``` `rcs_id` ``` - varchar of size 20
* ``` `creation_date` ``` - date

## Prefixes
Prefixes are the types of classes that are stored on the database. Using four characters, each prefix references a department at RPI. These were obtained using the YACS API. The listing of prefixes is stored in a JSON file found at `resources/prefixes.json`, and related PHP logic can be found in `resources/prefixes.php`. Additionally, each class must be assigned a prefix, which is stored in each entry of the categories table, under ``` `prefix` ```.

## Users
When a user logs into the SlateCrate application, the application logs a reference to their Rensselaer Computing System (RCS) username and a 0 to denote non-administrative status. If a user should be granted administrative access, the database can be updated using the following query to define a user as an administrator:

```sql
UPDATE `slatecrate` . `users` SET isadmin=1 WHERE rcs_id='<username>';
```

Similarly, a user can be demoted using the same query, except changing the SET portion to: `SET isadmin=0`. Note: the change will be reflected immediately, so this query should be used with caution.

### SQL Structure
Links are stored in the `users` table of the `slatecrate` database. The `users` table contains the following fields:
* ``` `user_id` ``` - integer - primary key
* ``` `rcs_id` ``` - varchar of size 50
* ``` `isadmin` ``` - integer of size - should only contain 0 or 1

### CAS Authentication
SlateCrate utilizes RPI’s Central Authentication Service for authentication into the application. This system provides ease-of-use for RPI students, who won’t be required to make an additional account, as well as added security as password details are not stored in SlateCrate. Information on how to use phpCAS, the library used to implement RPI’s CAS, can be [found here](https://wiki.jasig.org/display/CASC/phpCAS).

## Contributors
* Tristan Villamil ’18 was responsible for the development of the database-client interaction.
* Justin Etzine ’18 was responsible for the development of the user interface, the site’s visual appearance, and how the data was displayed on each page.
* Anjin Lima ’18 was responsible for site design, database design and implementation, and testing.

## Acknowledgements
SlateCrate’s design is modified from the [Solid Multipurpose Theme](http://blacktie.co/2014/05/solid-multipurpose-theme/). Open Source dependencies used are listed in the Dependencies section, above. SlateCrate was created as a final project for the Fall 2015 Web Systems Development course, taught by Professor Richard Plokta at Rensselaer Polytechnic Institute.
