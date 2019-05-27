<?php
// Meldung: Datenschutz akzeptieren

//if (false) {
?>

<style>


    .accept-dsgvo {
        display: none;

        position: fixed;
        bottom: 100px;
        left: 20px;
        right: 20px;


        width: 40%;

        margin: auto;
        border-radius: 1px;
        background-color: rgba(73, 202, 167, 0.9);
        border: 1px rgba(123, 123, 123, 0.7) solid;
        color: #2b2a2a;
        z-index: 999999;

        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.25), 0 6px 20px 0 rgba(0, 0, 0, 0.25);
    }

    .accept-dsgvo > .container {
        display: flex;
        flex-wrap: wrap;
        border-radius: 1px;
        margin: 0;
        width: unset;
    }

    .accept-dsgvo > .container > .text-container {
        width: calc( 100% - 120px);
        padding: 12px;
        box-sizing: border-box;
    }

    .accept-dsgvo > .container > .button-container {
        width: 120px;

        box-sizing: border-box;

        border-radius: 0 1px 1px 0;

        -moz-transition: color 0.2s ease, background-color 0.2s ease;
        -webkit-transition: color 0.2s ease, background-color 0.2s ease;
        -ms-transition: color 0.2s ease, background-color 0.2s ease;
        transition: color 0.2s ease, background-color 0.2s ease;
    }

    .accept-dsgvo > .container > .button-container:hover {
        background-color: rgba(100,100,100,0.95);
        color: white;
        cursor: pointer;
    }

    .accept-dsgvo > .container a:hover {
        border-bottom-color: transparent;
        color: #214224 !important;
    }

    @media screen and (max-width: 980px) {
        .accept-dsgvo {
            width: 75%;
        }
    }

    @media screen and (max-width: 736px) {
        .accept-dsgvo {
            width: unset;
            left: 30px;
            right: 30px;
        }
    }

    /*@media screen and (max-width: 480px) {
        .accept-dsgvo {
            width: unset;
            left: 30px;
            right: 30px;
        }
    }*/

    @media screen and (max-width: 360px) {
        .accept-dsgvo {
            width: unset;
            left: 20px;
            right: 20px;
        }
    }

</style>


<div id="accept-dsgvo" class="accept-dsgvo">

    <div class="container">
        <div class="text-container">
            Bitte lesen Sie unsere <a href="?ref=datenschutz">Datenschutzerklärung</a> und akzeptieren diese bevor Sie diese Seite nutzen.
        </div>

        <div class="button-container">
            <div style="
                display: table;
                width: 100%;
                height: 100%;
                border-radius: 0 1px 1px 0;
            ">
                <div
                id="accept-dsgvo-btn"
                style="
                    display: table-cell;
                    vertical-align: middle;
                    border-radius: 0 1px 1px 0;
                    text-align: center;
                ">
                    Akzeptieren
                </div>
            </div>
        </div>
    </div>

</div>

<?php

?>

<script>


var func = function() {
    var acceptdsgvo = document.getElementById("accept-dsgvo");
    var acceptdsgvoBtn = document.getElementById("accept-dsgvo-btn");

    if (localStorage.getItem('accepted') == "1") {
        accepted();
    } else {
        acceptdsgvo.style.display = "block";
    }



    acceptdsgvoBtn.onclick = function() {
        localStorage.setItem('accepted', '1');
        accepted();
    }


    function accepted() {
        var acceptdsgvo = document.getElementById("accept-dsgvo");

        acceptdsgvo.style.display = "none";

        var iframes = document.getElementsByTagName("iframe");

        for (let f of iframes) {
            if (f.dataset.src!==undefined)
                f.src = f.dataset.src;
        }
    }

};


if (window.addEventListener) // W3C standard
{
  window.addEventListener('load', func, false); // NB **not** 'onload'
}
else if (window.attachEvent) // Microsoft
{
  window.attachEvent('onload', func);
}

</script>

<?php
 //}
?>
