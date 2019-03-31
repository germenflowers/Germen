<?
namespace PDV;

class Instagram
{
    const token = '1415426465.a7d0703.317535746cbf47728db9e1caeb7a131e';

    private function fetchData( $url ){
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
        $result = curl_exec( $ch );
        curl_close( $ch );

        return $result;
    }

    public function getPosts() {
        $result = self::fetchData( 'https://api.instagram.com/v1/users/self/media/recent/?access_token='.self::token);
        $result = json_decode( $result, true );
        $limit = 20;
        
        $posts = array(
            'username' => $result['data'][0]['user']['username']
        );
        foreach ( $result['data'] as $i => $data ) {
            if ( $i < $limit ) {
                $posts['ITEMS'][] = array(
                    'LINK' => trim($data['link']),
                    'IMAGE' => trim($data['images']['standard_resolution']['url'])
                );
            }
        }

        return $posts;
    }
}