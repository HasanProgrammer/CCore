@Extends('Layout')

@Start('Title' , 'DefaultPage')

@Start('StyleSheet')

    <link rel="stylesheet" href=[@root()."UI/StyleSheet/Controllers/DefaultController/Index.css"@]>

@Close('StyleSheet')

@Start('Body')



@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src=[@root()."UI/JavaScript/Controllers/DefaultController/Index.js"@]></script>

@Close('JavaScript')