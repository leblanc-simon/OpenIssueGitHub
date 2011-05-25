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

function setError(id)
{
  $('#' + id).addClass('error');
  $('#' + id).parent().children('label').addClass('error');
}

function unsetError(id)
{
  $('#' + id).removeClass('error');
  $('#' + id).parent().children('label').removeClass('error');
}

$(document).ready(function(){
  // On place le focus sur la liste déroulante de projet (pour les vieux navigateurs)
  if (!("autofocus" in document.createElement("input"))) {
    $("#project").focus();
  }
  
  // gestion des vérifications
  $('#submit_issue').submit(function(){
    var error = false;
    
    // vérification du nom
    $('#project option:selected').each(function() {
      if ($(this).val() == '') {
        setError('project');
        error = true;
      } else {
        unsetError('project');
      }
    });
    
    // vérification du nom
    if ($('#name').val() == '') {
      setError('name');
      error = true;
    } else {
      unsetError('name');
    }
    
    // vérification de l'adresse mail
    if ($('#email').val() == '') {
      setError('email');
      error = true;
    } else {
      unsetError('email');
    }
    
    // vérification du titre
    if ($('#title').val() == '') {
      setError('title');
      error = true;
    } else {
      unsetError('title');
    }
    
    // vérification de la description
    if ($('#body').val() == '') {
      setError('body');
      error = true;
    } else {
      unsetError('body');
    }
    
    return !error;
  });
});