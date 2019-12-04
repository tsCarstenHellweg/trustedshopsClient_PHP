<?php
// this is for my local config only. if you want to use this code, pls uncomment the function below and fill out the values.
if( is_file( __DIR__ . '/../../../trustedshops_local.php' ) )
{
    require_once __DIR__ . '/../../../trustedshops_local.php';
}

/*    <--- just delete this line and fill out the array-values below
function getTrustedShopsConfig()
{
    return array( 
          'tsID' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        , 'username' => ''
        , 'password' => ''
    );
}
// */

$size = 10;
$page = 0;

$allcount = array();
do
{        
    $config = getTrustedShopsConfig();
    $loginData = base64_encode( $config[ 'username' ] . ':' . $config[ 'password' ] );

    $url = 'https://api.trustedshops.com/rest/restricted/v2/shops/' . $config[ 'tsID' ] . '/products/reviews.json'; // ?size=' . $size . '&page='.$page;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
              'authorization: Basic ' . $loginData
            , 'cache-control: no-cache'
            , 'content-type: application/json'
         ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $resultJSON = json_decode( $response );

    echo '<h1>result</h1><pre>', print_r( $resultJSON, 1 ), '</pre>';
    
    $reviews = $resultJSON->response->data->shop->productReviews;    
    foreach( $reviews as $review )
    {        
        // show all the review-data
        echo '<h1>review</h1><pre>', print_r( $review, 1 ), '</pre>';

         
        // here are the fields, that you can access via the call.
        $reviewCreationDate = '' . $review->creationDate;
        $reviewComment      = '' . $review->comment;
        $reviewUID          = '' . $review->UID;
        
        $reviewProduct      = (array) $review->product; // [sku], [name], [imageUrl], [uuid], [url]        
        $reviewOrder        = (array) $review->order;   // [orderDate], [orderReference], [uid] .... review[ 'uid' ] is the same as review[ 'uuid' ]
        $reviewReviewer     = (array) $review->reviewer; // [uuid], [email]
    }        
    $page++;
    $count = '' . $resultJSON->response->responseInfo->count;
}
while( $count == $size );