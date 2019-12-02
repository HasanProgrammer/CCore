<?php

return
[

    /*___________________________________________________________
     |
     | You can configure the view template engine settings here
     |___________________________________________________________
    */

    'TemplateEngine' =>
    [
        "\[@(.*?)@\]"                  => '<?= $1 ?>'                            ,
        "\[!(.*?)!\]"                  => '<?= immunization($1) ?>'              ,
        "\[\((.*?)\)\]"                => '<?php dd($1) ?>'                      ,
        "\[%(.*?)%\]"                  => '<?php var_dump($1) ?>'                ,
        "@For\((.*?)\)"                => '<?php for($1): ?>'                    ,
        "@EndFor"                      => '<?php endfor; ?>'                     ,
        "@While\((.*?)\)"              => '<?php while($1): ?>'                  ,
        "@EndWhile"                    => '<?php endwhile; ?>'                   ,
        "@ForEach\((.*?)\)"            => '<?php foreach($1): ?>'                ,
        "@EndForEach"                  => '<?php endforeach; ?>'                 ,
        "@Continue"                    => '<?php continue; ?>'                   ,
        "@Break"                       => '<?php break; ?>'                      ,
        "@Return"                      => '<?php return; ?>'                     ,
        "@Return\((.*?)\)"             => '<?php return $1; ?>'                  ,
        "@Switch\((.*?)\)"             => '<?php switch($1): case 404 : ?>'      ,
        "@Case\((.*?)\)"               => '<?php case $1: ?>'                    ,
        "@EndCase"                     => '<?php break; ?>'                      ,
        "@Default"                     => '<?php default: ?>'                    ,
        "@EndSwitch"                   => '<?php endswitch; ?>'                  ,
        "@If\((.*?)\)"                 => '<?php if($1): ?>'                     ,
        "@ElseIf\((.*?)\)"             => '<?php elseif($1): ?>'                 ,
        "@Else"                        => '<?php else: ?>'                       ,
        "@Extends\((.*?)\)"            => '<?php $this->setLayout($1) ?>'        ,
        "@ExtendsArea\((.*?), (.*?)\)" => '<?php $this->setLayoutArea($1,$2) ?>' ,
        "@Partial\((.*?)\)"            => '<?php $this->renderPartials($1) ?>'   ,
        "@EndIf"                       => '<?php endif; ?>'                      ,
        "@Start\((.*?)\)"              => '<?php $this->section($1) ?>'          ,
        "@Start\((.*?), (.*?)\)"       => '<?php $this->section($1,$2) ?>'       ,
        "@Close\((.*?)\)"              => '<?php $this->end($1) ?>'              ,
        "@Range\((.*?)\)"              => '<?= $this->content($1) ?>'
    ]

];