/*=========================================================================================
    File Name: content-sidebar.js
    Description: Invoices list data tables configurations
    ----------------------------------------------------------------------------------------
    Item Name: CRM Application - Contorl business and marketing
    Author: Appsaeed
    Author URL: https://appsaeed.github.io
==========================================================================================*/

$(document).ready(function () {
  /***********************************
   *        js of small Slider        *
   ************************************/

  var sm_options = {
    start: [30, 70],
    behaviour: "drag",
    connect: true,
    range: {
      min: 20,
      max: 80,
    },
  };
  var smallSlider = document.getElementById("small-slider");
  noUiSlider.create(smallSlider, sm_options);

  if ($(".sidebar-sticky").length) {
    var headerNavbarHeight, footerNavbarHeight;

    // Header & Footer offset only for right & left sticky sidebar
    if ($("body").hasClass("content-right-sidebar") || $("body").hasClass("content-left-sidebar")) {
      headerNavbarHeight = $(".header-navbar").height();
      footerNavbarHeight = $("footer.footer").height();
    }
    // Header & Footer offset with padding for detached right & left dsticky sidebar
    else {
      headerNavbarHeight = $(".header-navbar").height() + 24;
      footerNavbarHeight = $("footer.footer").height() + 10;
    }

    $(".sidebar-sticky").sticky({
      topSpacing: headerNavbarHeight,
      bottomSpacing: footerNavbarHeight,
    });
  }
});
