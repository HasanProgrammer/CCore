@Extends('Layout')

@Start('Title', 'CCore-ErrorPage')

@Start('StyleSheet')

    <link rel="stylesheet" href="[@root()@]WWW/StyleSheet/Controllers/ErrorController/Index.css">

@Close('StyleSheet')

@Start('Body')

    <div id="Box_Error_Controller">
        <div id="Content_Box_Error_Controller">404</div>
    </div>

@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src="WWW/JavaScript/Controllers/ErrorController/Index.js"></script>

@Close('JavaScript')