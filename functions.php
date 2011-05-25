<?php
/*
    Copyright (c) 2011, Leblanc Simon <contact@leblanc-simon.eu>
    All rights reserved.
    
    Redistribution and use in source and binary forms, with or without modification, are permitted provided that
    the following conditions are met:
    
        * Redistributions of source code must retain the above copyright notice, this list of conditions
          and the following disclaimer.
        * Redistributions in binary form must reproduce the above copyright notice, this list of conditions
          and the following disclaimer in the documentation and/or other materials provided with the distribution.
        * The names of its contributors may be used to endorse or promote products derived from this software
          without specific prior written permission.
    
    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
    SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
    WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
    USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Submit an issue in GitHub
 * 
 * @param   array   $input    Array with title and bodyof issue
 * @param   string  $repo     The repository where we want submit issue
 * @param   string  $user     The username of the repository's owner where we want submit issue
 * @return  string            The URL of new issue, false if error
 */
function submit_issue($input, $repo, $user)
{
  $curl = curl_init();
  
  curl_setopt($curl, CURLOPT_URL, 'https://github.com/api/v2/json/issues/open/'.$user.'/'.$repo);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
  //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorisation: token '.GITHUB_TOKEN));
  $input['token'] = GITHUB_TOKEN;
  $input['login'] = GITHUB_LOGIN;
  
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
  curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
  
  $response       = curl_exec($curl);
  $headers        = curl_getinfo($curl);
  $error_number   = curl_errno($curl);
  $error_message  = curl_error($curl);
  
  curl_close($curl);
  
  if ($error_number === 0 && $headers['http_code'] === 201) {
    // On récupère l'URL de l'issue
    $datas = json_decode($response);
    return $datas->issue->html_url;
  } else {
    //var_dump($headers, $response, $error_number, $error_message);
    return false;
  }
}


/**
 * Initialize the data value of the form
 *
 * @return  array   The datas values
 */
function initDatas()
{
  $datas = array('name', 'email', 'title', 'body', 'project');
  $result = array();
  foreach ($datas as $data) {
    $result[$data] = '';
  }
  
  return $result;
}


/**
 * Get the datas submit by POST
 *
 * @return  array   A array with an array of the datas values and bool with error
 */
function getDatas()
{
  $datas = array('name', 'email', 'title', 'body', 'project');
  $send = array();
  $error = false;
  foreach ($datas as $data) {
    if (isset($_POST[$data]) === true && is_string($_POST[$data]) === true) {
      $send[$data] = (string)$_POST[$data];
    } else {
      $send[$data] = null;
      $error = true;
    }
  }
  
  return array($send, $error);
}