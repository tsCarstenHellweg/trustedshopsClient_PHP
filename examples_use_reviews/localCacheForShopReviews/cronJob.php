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
do
{        
    $config = getTrustedShopsConfig();
    $loginData = base64_encode( $config[ 'username' ] . ':' . $config[ 'password' ] );

    $url = 'https://api.trustedshops.com/rest/restricted/v2/shops/' . $config[ 'tsID' ] . '/reviews.json?size=' . $size . '&page='.$page;
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
    $metaInfo = $resultJSON->response->responseInfo;
    $reviews = $resultJSON->response->data->shop->reviews;

    foreach( $reviews as $review )
    {        
        // just in case you want to see all data of the reviews(eg for adding more data to your cache)
        // echo '<h1>review</h1><pre>', print_r( $review, 1 ), '</pre>';
        $email = '' . $review->consumerEmail;
        $order = '' . $review->orderReference;

        // MariaDB/mysql dialect here
        // if you use a different rdbms, pls modify accordingly
        $sql = "INSERT IGNORE INTO trustedShopsReviewCache( email, orderRef ) VALUES ( '" . $email . "', '" . $order . "');";

        /** @todo: do the actual inserts */
        echo $sql . '<br />';
    }        
    $page++;
}
while( $metaInfo->count == $size );