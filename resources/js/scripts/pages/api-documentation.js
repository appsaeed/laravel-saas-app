/*=========================================================================================
	File Name: api-documentation.js
	Description: show hide div for api documentation.
------------------------------------------------------------------------------
    Item Name: CRM Application
    Author: Appsaeed
    Author URL: https://appsaeed.github.io
==========================================================================================*/

$(document).ready(function () {
  let featureDescription = $(".features_description .title");
  featureDescription.hide();

  $("#contacts-api-div").show();

  function setFeature(feature) {
    featureDescription.each(function () {
      if (this !== feature) $(this).hide();
    });
    $("#" + feature).toggle();
  }

  $("#features li").click(function (e) {
    e.preventDefault();
    setFeature(this.id + "-div");
  });
});
