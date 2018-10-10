$(document).ready( function() {
  validateCookie(getCookie("username"));
  isAdmin();
  populateCategoriesForHeader();
  $(".home_page").on("click", function() {
    window.location.href = "home.html";
  });
  
  $(".write_review_header").on("click", function() {
    window.location.href = "review.html";
  });

  $(".category").on("click", function(event) {
    var category = event.target.id;
    var url = "subCategories.html?category="+category;
    window.location.href = url;
  });

  $(".categories").on("click", function(event) {
    var url = "categories.html";
    window.location.href = url;
  });
  $("#success").on("click", function(event) {
    var url = "success.html";
    window.location.href = url;
  });
  $('.wishlist').click(function(){        
        var url = "wishList.html";
        window.location.href = url;
  });
  $('.cart').click(function(){        
        var url = "cart.html";
        window.location.href = url;
  });

  $(".search").on("submit", function(event) {
    event.preventDefault();
    var query = $(".search_query").val();
    var url = "bookListings.html?query="+query;
    window.location.href = url;
  });

  $('ul.nav li.dropdown').hover(function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(200);
  }, function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(200);
  });
});
function isAdmin() {
  var admin = getCookie("admin");
  if(admin == 'Y')
    $(".admin").show();
}
function validateCookie(username) {
  $.ajax({
      url: '../server/registercheck.php',
      data: {
        "username": username,
        "purpose": "checkuser",
      },
      type: 'post',         
      success: function(result){
        if(result) {
          $(".logIn").hide();
          $('#logged-username').html(username);
          $(".loggedIn").show();
        }
        else {              
          $(".loggedIn").hide();
          $('#logged-username').html('');
          $(".logIn").show();
        }
      }
    });
}
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
          c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
      }
  }
  return "";
}
var populateCategoriesForHeader = function() {
  $.ajax({
    url: '../server/categories.php',
    type: 'post',
    data:{
      purpose:"getcategories"
    },
    success: function(result){
      var data = jQuery.parseJSON(result);    
      $.each( data, function( key, value ) {
        $('.category').append('<li><a href="#" id='+value['id']+'>'+value['name']+'</a></li>');
      });       
    },
    error: function() { alert("error loading PHP file");  }
  });
};