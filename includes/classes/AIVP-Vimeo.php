<?php
/**
 * Custom functions to consume laravel passport api
 */
class AIVP_Vimeo {

    private $token;

    function set_token( $data ) {

        $client_id = $data["client_id"];
        $client_secret = $data["client_secret"];

        $data = array(
            "grant_type" => "client_credentials",
            "scope" => "video_files"
        );

        $encode = $client_id.":".$client_secret;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.vimeo.com/oauth/authorize/client",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: basic ".base64_encode($encode)."",
                "accept: application/json",
                "content-type: application/vnd.vimeo.*+json;version=3.4",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $responseInfo = curl_getinfo($curl);
        
        curl_close($curl);

        if ($err) {
            set_error_handler($err);
        } else {

            if ($responseInfo['http_code'] == 200) {

                $this->token = json_decode($response);

            } else {

                return "Login to api faild status 403";

            }
        }
    }

    function get_token() {
        return $this->token;
    }

    function get_videos() {
        // return $this->token;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.vimeo.com/me/videos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: " . $this->token->token_type . " " . $this->token->access_token . "",
                "Content-Type: 	application/vnd.vimeo.*+json;version=3.4",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $responseInfo = curl_getinfo($curl);

        curl_close($curl);

        if ($err) {
            set_error_handler($err);
            $res = array(
                'status' => '500',
                'message' => $response,
                'success' => false
            );
            return $res;
        } else {

            if ($responseInfo['http_code'] == 200) {

                $res = array(
                    'status' => '200',
                    'response' => $response,
                    'success' => true
                );
                return $res;

            } else {

                set_error_handler("Fatal error. Server error");
                error_log("Fatal error. Server error");

                $res = array(
                    'status' => '500',
                    'message' => $response,
                    'success' => false
                );
                return $res;

            }
        }
    }


    function post( $url, $data ) {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: " . $this->token->token_type . " " . $this->token->access_token . "",
                "cache-control: no-cache",
                "content-type: multipart/form-data",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $responseInfo = curl_getinfo($curl);

        curl_close($curl);

        if ($err) {
            set_error_handler($err);
            $res = array(
                'status' => '500',
                'message' => $response,
                'success' => false
            );
            return $res;
        } else {

            if ($responseInfo['http_code'] == 200) {

                $res = array(
                    'status' => '200',
                    'response' => $response,
                    'success' => true
                );
                return $res;

            } else {

                set_error_handler("Fatal error. Server error");
                error_log("Fatal error. Server error");

                $res = array(
                    'status' => '500',
                    'message' => $response,
                    'success' => false
                );
                return $res;

            }
        }
    }
}