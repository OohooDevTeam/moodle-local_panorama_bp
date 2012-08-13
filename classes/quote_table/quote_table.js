$(document).ready(function(){  
    //Context Menu.
  
    function remove_row(menu_item, caller) {
        if(menu_item == 'remove_row') {
            caller.remove();
        }
        
        update_totals();
    }
  
    function add_row() {
        var new_row = $('<tr class="item_row"><td class="qty" ><input name="qty[]" value="1" type="text"/></td><td class="description" ><input name="description[]" type="text"/></td><td class="unit_price"><input name="unit_price[]" type="text"/></td><td class="line_total">$0.00</td></tr>');
      
        $('#price_table tbody #subtotal_row').before(new_row);
      
        new_row.contextMenu({
            menu: "aMenu"
        }, remove_row);
      
    }
    
    $('.item_row').contextMenu({
        menu: "aMenu"
    }, remove_row);
  
    //Setup the price formatting for the unit price section.
    $(document).on('blur', '.unit_price input',  null, function() {
        $(this).formatCurrency({
            colorize: true, 
            negativeFormat: '-%s%n', 
            roundToDecimalPlace: 2
        });
    });
  
    $(document).on('keyup', '.unit_price input', null, function(e) {
        var e = window.event || e;
        var keyUnicode = e.charCode || e.keyCode;
        if (e !== undefined) {
            switch (keyUnicode) {
                case 16:
                    break; // Shift
                case 17:
                    break; // Ctrl
                case 18:
                    break; // Alt
                case 27:
                    this.value = '';
                    break; // Esc: clear entry
                case 35:
                    break; // End
                case 36:
                    break; // Home
                case 37:
                    break; // cursor left
                case 38:
                    break; // cursor up
                case 39:
                    break; // cursor right
                case 40:
                    break; // cursor down
                case 78:
                    break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
                case 110:
                    break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
                case 190:
                    break; // .
                default:
                    $(this).formatCurrency({
                        colorize: true, 
                        negativeFormat: '-%s%n', 
                        roundToDecimalPlace: -1, 
                        eventOnDecimalsEntered: true
                    });
            }
        }
    });
  
    //Update all the totals.
    function update_totals() {
        var sum = 0;
        $('.item_row').each(function(index, item){
            var qty = $(item).find('.qty input').val();
            var unit_price = $(item).find('.unit_price input').val();
            unit_price = unit_price.substring(1).replace(/,/g, '');
          
            var line_total = $(item).find('.line_total');
            var price = unit_price * qty;
            line_total.html(price);
            sum += price;
            line_total.formatCurrency({
                colorize: true, 
                negativeFormat: '-%s%n', 
                roundToDecimalPlace: 2
            });
        });
        
        $('#total').html(sum).formatCurrency({
            colorize: true, 
            negativeFormat: '-%s%n', 
            roundToDecimalPlace: 2
        });
    }
  
    $(document).on('blur', '.unit_price input',  null, update_totals);
    $(document).on('blur', '.qty input',  null, update_totals);
  
    
    $('#add_row_cell').click(add_row);

});