<h2><?=$users[$userTblArray['uname_fld']] ?></h2>
Email : <?=$users[$userTblArray['email_fld']] ?><br />
Country :<?=$users[$userTblArray['country_fld']] ?><br />
<script>
$("#myprojects_tab").click(function(){
$("#myprojects_tab").toggleClass("open");
});
</script> 