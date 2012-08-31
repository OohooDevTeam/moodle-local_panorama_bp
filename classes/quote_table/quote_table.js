/**
 * ************************************************************************
 * *                     Panorama Buisness Process                       **
 * ************************************************************************
 * @package     local                                                    **
 * @subpackage  Panorama Buisness Process                                **
 * @name        Panorama Buisness Process                                **
 * @copyright   oohoo.biz                                                **
 * @link        http://oohoo.biz                                         **
 * @author      Andrew McCann                                            **
 * @license     Copyright                                                **
 * ************************************************************************
 * ********************************************************************** */    
/**
 * Removes a row from the table.
 * 
 * @param menu_item Then menu item that as selected. Should always be delete
 *  since it is the only one that should exist.
 * @param caller The row on which the selected option was used. In this case
 *  it woudl be the row to be removed.
 */
function remove_row(menu_item, caller) {
    if(menu_item == 'remove_row') {
        caller.remove();
    }
        
    //Update all totals after the row was removed.
    update_totals();
}
  
/**
 * Function to add a row to the quote table.
 */
function add_row() {
    var new_row = $('<tr class="item_row"><td class="qty" ><input name="qty[]" value="1" type="text"/></td><td class="description" ><input name="description[]" type="text"/></td><td class="unit_price"><input name="unit_price[]" type="text"/></td><td class="line_total">$0.00</td></tr>');
      
    $('#price_table tbody #subtotal_row').before(new_row);
        
    //Apply the context menu to this new row.
    new_row.contextMenu({
        menu: "aMenu"
    }, remove_row);
      
}
    
      
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

//Main script!
$(document).ready(function(){  
    
    //Creat the context menu for any rows that already exist.
    $('.item_row').contextMenu({
        menu: "aMenu"
    }, remove_row);
    
    //fill in the totals for any rows that already exist.
    update_totals();    
  
    //Setup the price formatting for the unit price section.
    $(document).on('blur', '.unit_price input',  null, function() {
        $(this).formatCurrency({
            colorize: true, 
            negativeFormat: '-%s%n', 
            roundToDecimalPlace: 2
        });
    });
  
    //Stop enter from submitting the form.
    $(document).on('keypress', '#price_table input', null, function(e) {
        if(e.charCode == 13 || e.keyCode == 13) {
            e.preventDefault();
            e.stopPropagation();
            
            //Simulate pressing tab.
            $(this).blur();
            var thisClass = '.' + $(this).parent().attr('class');
            $(this).parent().parent().next().find(thisClass + ' input').focus();
        }
    });
  
    //Format the unit price to be a dollar amount whenever a key is released
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

    
    //Whenever qty or unit price blurs and therefore was changed update the 
    //totals.
    $(document).on('blur', '.unit_price input',  null, update_totals);
    $(document).on('blur', '.qty input',  null, update_totals);
  
    //When add row button is pressed this will add a row.
    $('#add_row_cell').click(add_row);

});