@Extends('Layout')

@Start('Title' , 'CCore-HomePage')

@Start('StyleSheet')

    <link rel="stylesheet" href="[@root()@]WWW/StyleSheet/Controllers/HomeController/Index.css">

@Close('StyleSheet')

@Start('Body')

    <div id="Box_Home_Controller">
        <div id="Content_Box_Home_Controller">Welcome to CCore</div>
    </div>

@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src="[@root()@]WWW/JavaScript/Controllers/HomeController/Index.js"></script>

@Close('JavaScript')