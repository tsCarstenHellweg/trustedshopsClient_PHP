/**
 * @author Enrico Taguebou
 */


var getTSstars = function(zahl, htmlText){
    const starPercentage =(zahl/5 * 100).toFixed(2);
    const starPercentageRounded = starPercentage +'%';
    var myimg20PX = '';
    var starElement= '<div id="myTsImgae">'+myimg20PX+'</div><div id="" class="stars-outer"><div class="stars-inner"></div></div> <div id="tsNote"></div>';  
    $("#trustedStars_Output").html(starElement); 
    $('.stars-inner').width(starPercentageRounded);
    $("#tsNote").append(htmlText);
}

var _tsid = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; // <---- replace
var ts_item = "trustedStars_"+_tsid; 
var result_ts; 
var count;
var description;
if(sessionStorage.getItem(ts_item)){
    var tsSessionData = sessionStorage.getItem(ts_item);
    var obj = JSON.parse(tsSessionData);
    result_ts = obj.qualityIndicators.reviewIndicator.overallMark;
    count = obj.qualityIndicators.reviewIndicator.activeReviewCount;
    description= obj.qualityIndicators.reviewIndicator.overallMarkDescriptionGUILang;
    if (count > 0) 
    { 
        var content = '<div id="noteDescription">' 
                        + ' ' + description 
                        + ' </div><div>'
                        + '<span itemprop="ratingValue">' 
                        + result_ts 
                        + '</span> /' + '<span itemprop="bestRating">' 
                        + 5 + '</span> of ' 
                        + '<span itemprop="ratingCount"> (' + count + ')   </span> </div>'; 
    }    
    getTSstars(result_ts, content);    
}
else 
{
    var api_URL = 'https://api.trustedshops.com/rest/public/v2/shops/' + _tsid + '/quality/reviews.json';
    $.get( api_URL, function( data ) {
        result_ts = data.response.data.shop.qualityIndicators.reviewIndicator.overallMark;
        count = data.response.data.shop.qualityIndicators.reviewIndicator.activeReviewCount;
        description = data.response.data.shop.qualityIndicators.reviewIndicator.overallMarkDescriptionGUILang;
        var shopName = data.response.data.shop.name;
        var tsReturnData = data.response.data.shop;
        if (count > 0) { 
            var content = '<div id="noteDescription"> '+ description + '  ' 
                        +'</div><div>'+ '<span itemprop="ratingValue">' + result_ts 
                        + '</span> /' + '<span itemprop="bestRating">' + 5 
                        + '</span> of ' + '<span itemprop="ratingCount"> (' + count + ')   </span> </div>';
            getTSstars(result_ts, content);                  
            sessionStorage.setItem(ts_item,
            JSON.stringify(tsReturnData));
        }
    });
}

