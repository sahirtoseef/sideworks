$(document).ready(function(){
  $("#home a:contains('Dashboard')").parent().addClass('active');

  // Posts
  $("#allPost  a:contains('Posts'),#AdNwPost  a:contains('Posts')").parent().addClass('active');
  $("#allPost  a:contains('All Post')").parent().addClass('active');
  $("#AdNwPost a:contains('Add New')").parent().addClass('active');

  // Media
  $("#media a:contains('Media'), #AdNwMedia a:contains('Media')").parent().addClass('active');
  $("#media a:contains('Librery')").parent().addClass('active');
  $("#AdNwMedia a:contains('Add New')").parent().addClass('active');

  //  Features 
  $("#Allfeat a[att='parfea'],#AdNwFeat a[att='parfea'],#CustFeat a[att='parfea']" ).parent().addClass('active');
  $("#Allfeat a[href='Allfeat.php']").parent().addClass('active');
  $("#AdNwFeat a:contains('Add New')").parent().addClass('active');
  $("#CustFeat a:contains('Customize Features')").parent().addClass('active');

 // Contact Forms
  $("#ContactForms a[att='mycontactpar'] , #AdNwCont a[att='mycontactpar']" ).parent().addClass('active');
  $("#ContactForms a:contains('Contact Form')").parent().addClass('active');
  $("#AdNwCont a:contains('Add New')").parent().addClass('active'); 

  // Appearance
  $("#AppearTheme a:contains('Appearance'),#GenerSettTheme a:contains('Appearance')" ).parent().addClass('active'); 
  $("#AppearTheme a:contains('Themes')" ).parent().addClass('active');
  $("#GenerSettTheme a:contains('General Settings')" ).parent().addClass('active');
// AllUsers
  $("#AllUsers a[att='users'],#AdNwUser a[att='users']" ).parent().addClass('active'); 
  $("#AllUsers a:contains('All Users')" ).parent().addClass('active');
  $("#AdNwUser a:contains('Add New')" ).parent().addClass('active');

  // ToolImport
  $("#ToolImport a:contains('Tools'),#ToolExport a:contains('Tools')" ).parent().addClass('active'); 
  $("#ToolImport a:contains('Import')" ).parent().addClass('active');
  $("#ToolExport a:contains('Export')" ).parent().addClass('active');


// Forms
  $("#Forms a:contains('Forms')").parent().addClass('active'); 

//Google Analytics
  $("#GoogleAnalytics a:contains('Google Analytics')").parent().addClass('active'); 


});
