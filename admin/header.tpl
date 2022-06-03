<div id="header" class="clearfix"></div>

<div id="sidenavi">
<ul>
<!--{each menu}-->
<!--{def menu/act}-->
<li><a href="?act={val menu/act}">{val menu/title}</a></li>
<!--{/def}-->
<!--{ndef menu/act}-->
<li class="tit">{val menu/title}</li>
<!--{/ndef}-->
<!--{/each}-->
</ul>
</div>
