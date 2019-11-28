@Extends('Layout')

@Start('Title' , 'DefaultPage')

@Start('StyleSheet')

    <link rel="stylesheet" href=[@root()."UI/StyleSheet/Controllers/HasanController/Index.css"@]>

@Close('StyleSheet')

@Start('Body')



@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src=[@root()."UI/JavaScript/Controllers/HasanController/Index.js"@]></script>

@Close('JavaScript')