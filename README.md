<br>
<p align="center">
    <a href="https://github.com/othneildrew/Best-README-Template">
        <img src="public/img/logo.png" alt="Logo" width="80" height="80">
    </a>
    <h3 align="center">University Portal</h3>
    <p align="center">
        A PHP CRUD interface!
        <br>
        <a href="https://github.com/jaredible/CS4610-Project-1"><strong>Explore the docs &#187;</strong></a>
        <br>
        <br>
        <a href="https://umsl.jaredible.net/cs/4610/project/1">View Demo</a>
        &middot;
        <a href="https://github.com/jaredible/CS4610-Project-1/issues">Report Bug</a>
        &middot;
        <a href="https://github.com/jaredible/CS4610-Project-1/issues">Request Feature</a>
    </p>
</p>



## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
* [Contributing](#contributing)
* [Acknowledgements](#acknowledgements)



## About The Project

[![University Portal][project-screenshot]](https://umsl.jaredible.net/cs/4610/project/1)

### Built With

* [Fomantic-UI](https://fomantic-ui.com/)
* [JQuery](https://jquery.com)
* [PHP](https://www.php.net/)
* [MySQL](https://www.mysql.com/)
* [XAMPP](https://www.apachefriends.org)



## Getting Started

These are instructions of how you may set up your project locally.
To get a local copy up and running follow these simple steps.

### Prerequisites

* PHP 5.6+
* MySQL 8.0+
* XAMPP 5.6+

### Installation

1. Navigate into your `htdocs` directory
2. Clone the repo
```sh
git clone https://github.com/jaredible/CS4610-Project-1.git
```
3. Open phpMyAdmin and run the `database.sql` script
4. Enter your database configuration in `config.php`
```PHP
$config = array(
    'db' => array(
        'host' => 'localhost',
        'user' => 'ENTER YOUR USERNAME',
        'pass' => 'ENTER YOUR PASSWORD',
        'name' => 'university'
    )
);
```



## Usage

- In your browser, you will need to navigate to `localhost/CS4610-Project-1/public` to view the project.
- To login, use the same database username and password.



## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/<feature-name>`)
3. Commit your Changes (`git commit -m 'Added <feature-name>'`)
4. Push to the Branch (`git push origin feature/<feature-name>`)
5. Open a Pull Request



## Acknowledgements

* [Intro.js](https://introjs.com/)
* [Best-README-Template](https://github.com/othneildrew/Best-README-Template)



## Contact

Jared Diehl - [@jaredmdiehl](https://twitter.com/jaredmdiehl) - jared@jaredible.net

Project Link: [https://github.com/jaredible/CS4610-Project-1](https://github.com/jaredible/jaredible/CS4610-Project-1)



[project-screenshot]: images/screenshot.png
