//Immediately-Invoked Function Expression (IIFE)
(function(){

    //Event to update the price of the items on the cart
    function updateTotalCartPrice(){
        priceElement = $("#totalCart");
        priceElementText = "";
  
        items = $(".itemOnTheCart");
        totalPrice = 0;
        
        for(i=0; i < items.length; ++i){
          item = items[i];
  
          itemPrice = $(item).find("[item-price]").attr("item-price");
          itemQuantity = $( item ).find("[item-quantity]").attr("item-quantity");
          
          totalPrice += itemPrice * itemQuantity;
        }
  
        priceElementText = `Total ${ totalPrice}€`;
        priceElement.text(priceElementText);
  
      }

    //Event to update the number of Items on the car that is shown
    function updateTotalCartItems(){
        cartElement = $("#cartLink");
        hrefTotalitems = "/cart/totalItems";
        totalElements= -1;
        $.get(hrefTotalitems, function(data){
          totalElements = data.totalCart;
          cartElementText = "Cart (" + totalElements + ")";      
          cartElement.text(cartElementText);
        });   
      }
  

    //Events to add a post to the cart 
    $("#buyPost").click(function(event){
      event.preventDefault();
      idPost = $(this).attr('post-id');
      const href = `/cart/post/add/${idPost}`;
      $.get(href, function() {
        updateTotalCartItems();
      });

    });

    //Event to remove items from a cart
    $("a.removeProduct").click(function (e){
        e.preventDefault();
        id = $(this).attr("item-id");
        parameters = "id=" + id;
        href = `/cart/post/delete/${id}`;
  
        $.post(href, parameters);
  
        $(`#item-${id}`).remove(); 
  
  
        updateTotalCartPrice();
  
        updateTotalCartItems();
    });


})();
