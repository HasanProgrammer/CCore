@If($CountPages != 1)
    <div class="Box_Pagination_Links">
        @If($NumberPage > 1)
            <a href="?Page=1">
                <div class="Link_First_Page"></div>
            </a>
            <a href="?Page=[!$NumberPageFromUrl-1!]">
                <div class="Link_Previous"></div>
            </a>
            @For($i = $NumberPage - 4; $i < $NumberPage; $i++)
                @If($i > 0)
                    <a href="?Page=[!$i!]">
                        <div class="Links">
                            <div class="TextLink">[!$i!]</div>
                        </div>
                    </a>
                @EndIf
            @EndFor
        @EndIf
        <div class="Link">
            <div class="Text_Single_Link">[!$NumberPage!]</div>
        </div>
        @For($i = $NumberPage + 1; $i <= $CountPages; $i++)
            <a href="?Page=[!$i!]">
                <div class="Links">
                    <div class="TextLink">[!$i!]</div>
                </div>
            <a>
            @If($i >= $NumberPage + 4)
                @Break
            @EndIf
        @EndFor
        @If($NumberPage != $CountPages)
            <a href="?Page=[!$NumberPageFromUrl+1!]">
                <div class="Link_Next"></div>
            <a>
            <a href="?Page=[!$CountPages!]">
                <div class="Link_Last_Page"></div>
            <a>
        @EndIf
    </div>
@EndIf
