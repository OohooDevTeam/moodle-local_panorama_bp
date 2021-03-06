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
$(function() { 
    $('#add_row_cell img').remove();
        
    $('#price_table input').each(function(index, item){
        var rent = $(item).parent();
        rent.append($(item).val());
        $(item).remove();
    });
    
    $('#price_table td').filter(':not(.line_total)').filter(':not(#total)').css('text-align', 'left');
    
    $('.unit_price, .line_total, #total').formatCurrency({
        colorize: true, 
        negativeFormat: '-%s%n', 
        roundToDecimalPlace: 2
    });
});

