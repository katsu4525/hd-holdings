$(function () {
  var setCover = function () {
    if ($("#main_visual").length) {
      var spacer = $("#container").css("padding-top");
      spacer = parseInt(spacer.replace("px", ""));
      var windowHeight = $(window).height() - spacer;
      $("#main_visual").height(windowHeight);
    }
  };
  setCover();

  var animateCover = function () {
    if ($("#main_visual").length) {
      $("#main_visual .relative").each(function () {
        var parent = $(this);
        var nbOfVisus = parent.find(".one_visu").length - 1;
        parent.find(".one_visu").eq(nbOfVisus).animate({ opacity: 0 }, 1200, function () {
          parent.find(".one_visu").eq(nbOfVisus).prependTo(parent).css({ opacity: 1 });
        });
      });

    }
  };
  if ($("#main_visual").length) {
    setInterval(function () {
      animateCover();
    }, 4000);
  }

  var animateImgSp = function () {
    if ($(".specialSpSwitch").length) {
      $(".specialSpSwitch").each(function () {
        var element = $(this);
        var nbOfVisus = element.find(".absolute").length - 1;
        element.find(".absolute").eq(nbOfVisus).animate({ opacity: 0 }, 1000, function () {
          element.find(".absolute").eq(nbOfVisus).prependTo(element.find(".relative")).css({ opacity: 1 });
        });
      });

    }
  };
  if ($(".specialSpSwitch").length) {
    setInterval(function () {
      animateImgSp();
    }, 3000);
  }

  var smallMenu = function () {
    var scrollValue = $(window).scrollTop();
    if ($("#header").hasClass("moving")) {
      $("#header").removeClass("smaller");
    } else {
      if (scrollValue > 300) {
        if (!$("#header").hasClass("smaller")) {
          $("#header").addClass("smaller");
        }
      } else {
        if ($("#header").hasClass("smaller")) {
          $("#header").removeClass("smaller");
        }
      }
    }
  };
  smallMenu();

  $(".anchor a").on("click", function () {
    var goTo = $(this).attr("href");
    goTo = $(goTo).offset().top;
    var headSize = $("#hd-top").height();
    goTo = goTo - (headSize - 1);
    $("html,body").animate({ scrollTop: goTo }, 400);
    return false;
  });

  var lazyLoad = function () {
    if ($(".animate_contents").length) {
      $(".animate_contents .oneLine").each(function (e) {
        if (!$(this).hasClass("loaded")) {
          var scrollValue = $(window).scrollTop();
          var windowHeight = $(window).height();
          var offsetTop = $(this).offset().top;
          var decal = 300;
          var delay = 250;
          if ($(".ifSp").css("display") == "block") {
            decal = 50;
            delay = 150;
          }
          if (windowHeight + scrollValue - decal >= offsetTop) {
            $(this).addClass("loaded");
            $(this).find(".lazyload").each(function (j) {
              var thisImage = $(this);
              setTimeout(function () {
                thisImage.parents(".box").addClass("loaded");
              }, j * delay);
            });
          }
          var decal2 = 500;
          if (windowHeight + scrollValue + decal2 >= offsetTop) {
            $(this).find(".lazyload").each(function (j) {
              var thisImage = $(this);
              if (!thisImage.hasClass("over")) {
                thisImage.addClass("over").attr("src", thisImage.attr("lazyload"));
              }
            });
          }
        }
      });
    }
  };
  //lazyLoad();

  var showToTop = function () {
    var scrollValue = $(window).scrollTop();
    var windowHeight = $(window).height();
    if (scrollValue > windowHeight) {
      if (!$("#to_top").hasClass("showed")) {
        $("#to_top").addClass("showed");
      }
    } else {
      if ($("#to_top").hasClass("showed")) {
        $("#to_top").removeClass("showed");
      }
    }
  };
  showToTop();

  $("#to_top").on("click", function () {
    $("html,body").animate({ scrollTop: 0 }, 500);
    return false;
  });

  $("#menuOpener").on("click", function () {
    if ($(this).hasClass("opened")) {
      $(this).removeClass("opened");
      $("#nav").removeClass("opened");
      $("#container").removeClass("menuOpen");
      var scrollValue = $("body").attr("scrollval");
      $("html,body").animate({ scrollTop: scrollValue }, 0);
      $("header").removeClass("menuOpen");  // add
    } else {
      var scrollValue = $(window).scrollTop();
      $("body").attr("scrollval", scrollValue);
      $(this).addClass("opened");
      $("#nav").addClass("opened");
      $("#container").addClass("menuOpen");
      $("header").addClass("menuOpen");  // add
    }
    return false;
  });

  var showMainImages = function () {
    if ($(".title_block img").length) {
      $(".title_block img").addClass("ready");
    }
    if ($(".products .title_block_img").length) {
      $(".products .title_block_img").addClass("ready");
    }
    if ($(".title_block_img img").length) {
      $(".title_block_img img").addClass("ready");
    }
    if ($("#main_visual").length) {
      $("#main_visual").addClass("ready");
    }
  };


  var adaptHeight = function (parent, target) {
    if ($(parent).length) {
      $(parent).each(function () {
        $(this).find(target).css({ "height": "auto" });
        var maxHeight = -1;
        $(this).find(target).each(function () {
          maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });
        $(this).find(target).each(function () {
          $(this).height(maxHeight);
        });
      });

    }
  };

  var adaptHeights = function () {
    adaptHeight("#products_list ul", "li a .title");
    adaptHeight("#products_list ul", "li a .text");
  };
  adaptHeights();



  $(window).resize(function () {
    setCover();
    smallMenu();
    lazyLoad();
    showToTop();
    adaptHeights();
  });

  $(window).scroll(function () {
    lazyLoad();
    smallMenu();
    showToTop();
  });

  $(window).load(function () {
    lazyLoad();
    smallMenu();
    showToTop();
    showMainImages();
    setCover();
    adaptHeights();
  });




  var checkField = function (element, option) {
    var value;
    var type = element.attr("type");
    if (type == "textarea") {
      value = element.html();
    } else if (type == "checkbox") {
      value = element.prop("checked");
    } else {
      value = element.val();
    }
    if (value && value != "") {
      if (option == "email") {
        if (value.match(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    } else {
      return false;
    }
  };

  $("#check").on("click", function (e) {
    $(".error").removeClass("error");
    var finalCheck = true;
    var radios = $(".radios input:checked").length;
    if (radios == 0) {
      finalCheck = false;
      $(".radios").addClass("error");
    }
    var issetCheck = ["full_name", "tel", "mail", "message"];
    var element;
    $.each(issetCheck, function (index, value) {
      element = $("[name=" + value + "]");
      if (element.val() == "") {
        element.parents("tr").addClass("error");
        finalCheck = false;
      } else {
        if (value == "mail") {
          if (!element.val().match(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i)) {
            finalCheck = false;
            element.parents("tr").addClass("error");
          }
        }
      }
    });

    if (finalCheck === false) {
      e.preventDefault();
      $("#hd-top").addClass("moving").removeClass("smaller");

      setTimeout(function () {
        var headSize = $("#hd-top").height();
        var goTo = $(".error").eq(0).offset().top - headSize;
        $("html,body").animate({ scrollTop: goTo }, 200);
      }, 400);
      return false;
    }
  });


  function loadArticle(targetElement, loadPage, nb_posts, hideBtn) {
    $.ajax({
      url: "/wordpress/wp-admin/admin-ajax.php",
      type: "POST",
      data: "action=infinite_scroll&page_no=" + loadPage + "&loop_file=loop&nb_posts=" + nb_posts,
      success: function (_html) {
        $(targetElement).append(_html);
        if (hideBtn === true) {
          setTimeout(function () {
            $(".more").remove();
          }, 10);
        }
      }
    });
    return false;
  }

  $(".loadMore").on("click", function () {
    var targetElement = $(this).attr("target");
    var nb_posts = $(this).attr("nb_posts");
    var loadPage = parseInt($(this).attr("next"));
    var nextPage = parseInt($(this).attr("next")) + 1;
    var maxPage = parseInt($(this).attr("max"));
    var hideBtn = false;
    $(this).attr("next", nextPage);
    if (loadPage * nb_posts >= maxPage) {
      hideBtn = true;
    }
    loadArticle(targetElement, loadPage, nb_posts, hideBtn);
    return false;
  });

});