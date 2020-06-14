<?php
require('./global/cooldown.php');
$_SESSION['amount'] = null;
?>
<html>
    <head>
        <title>Chup of Fortune</title>
        <link rel="stylesheet" href="./assets/main.css" type="text/css" />
        <script type="text/javascript" src="./assets/Winwheel.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    </head>
    <body>
    <br/><br/><br/><br/>
        <div align="center">

            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>

                    </td>
                    <td width="500" height="700" class="the_wheel" align="center" valign="center">
                        <canvas id="canvas" width="800" height="1000">
                            <p style="{color: white}" align="center">Sorry, your browser doesn't support canvas. Please try another.</p>
                        </canvas>
                    </td>
                </tr>
                <tr>
                    <td></td>

                    <td>
                        <div class="power_controls" align="center">
                            <br />
                            <br />
                            <br />
                            <img class="spinButton" id="spin_button" src="./images/spin.png" alt="Spin" onClick="startSpin();" />
                            <br /><br />

                        </div>
                    </td>

                </tr>
            </table>
        <script>
            // Create new wheel object specifying the parameters at creation time.
            let theWheel = new Winwheel({
                'outerRadius'     : 400,        // Set outer radius so wheel fits inside the background.
                'innerRadius'     : 120,         // Make wheel hollow so segments don't go all way to center.
                'textFontSize'    : 50,         // Set default font size for the segments.
                'textOrientation' : 'vertical', // Make text vertical so goes down from the outside of wheel.
                'textAlignment'   : 'outer',    // Align text to outside of wheel.
                'numSegments'     : 24,         // Specify number of segments.
                'segments'        :             // Define segments including colour and text.
                [                               // font size and test colour overridden on backrupt segments.
                   {'fillStyle' : '#ee1c24', 'text' : '3'},
                   {'fillStyle' : '#3cb878', 'text' : '4'},
                   {'fillStyle' : '#f6989d', 'text' : '6'},
                   {'fillStyle' : '#00aef0', 'text' : '7'},
                   {'fillStyle' : '#f26522', 'text' : '5'},
                   {'fillStyle' : '#000000', 'text' : 'NO MONEY', 'textFontSize' : 30, 'textFillStyle' : '#ffffff'},
                   {'fillStyle' : '#e70697', 'text' : '3'},
                   {'fillStyle' : '#fff200', 'text' : '6'},
                   {'fillStyle' : '#f6989d', 'text' : '7'},
                   {'fillStyle' : '#ee1c24', 'text' : '3'},
                   {'fillStyle' : '#3cb878', 'text' : '5'},
                   {'fillStyle' : '#f26522', 'text' : '8'},
                   {'fillStyle' : '#a186be', 'text' : '3'},
                   {'fillStyle' : '#fff200', 'text' : '4'},
                   {'fillStyle' : '#00aef0', 'text' : '6'},
                   {'fillStyle' : '#ee1c24', 'text' : '10'},
                   {'fillStyle' : '#f6989d', 'text' : '5'},
                   {'fillStyle' : '#f26522', 'text' : '4'},
                   {'fillStyle' : '#3cb878', 'text' : '9'},
                   {'fillStyle' : '#000000', 'text' : 'NO MONEY', 'textFontSize' : 30, 'textFillStyle' : '#ffffff'},
                   {'fillStyle' : '#a186be', 'text' : '6'},
                   {'fillStyle' : '#fff200', 'text' : '7'},
                   {'fillStyle' : '#00aef0', 'text' : '8'},
                   {'fillStyle' : '#ffffff', 'text' : '50', 'textFontSize' : 50}
                ],
                'animation' :           // Specify the animation to use.
                {
                    'type'     : 'spinToStop',
                    'duration' : 10,    // Duration in seconds.
                    'spins'    : 3,     // Default number of complete spins.
                    'callbackFinished' : alertPrize,
                    'callbackSound'    : playSound,   // Function to call when the tick sound is to be triggered.
                    'soundTrigger'     : 'pin'        // Specify pins are to trigger the sound, the other option is 'segment'.
                },
                'pins' :				// Turn pins on.
                {
                    'number'     : 24,
                    'fillStyle'  : 'silver',
                    'outerRadius': 4,
                }
            });

            // Loads the tick audio sound in to an audio object.
            let audio = new Audio('./sounds/tick.mp3');

            // This function is called when the sound is to be played.
            function playSound()
            {
                // Stop and rewind the sound if it already happens to be playing.
                audio.pause();
                audio.currentTime = 0;

                // Play the sound.
                audio.play();
            }

            // Vars used by the code in this page to do power controls.
            let wheelPower    = 0;
            let wheelSpinning = false;

            // -------------------------------------------------------
            // Function to handle the onClick on the power buttons.
            // -------------------------------------------------------
            function powerSelected(powerLevel)
            {
                // Ensure that power can't be changed while wheel is spinning.
                if (wheelSpinning === false) {
                    // Reset all to grey incase this is not the first time the user has selected the power.
                    document.getElementById('pw1').className = "";
                    document.getElementById('pw2').className = "";
                    document.getElementById('pw3').className = "";

                    // Now light up all cells below-and-including the one selected by changing the class.
                    if (powerLevel >= 1) {
                        document.getElementById('pw1').className = "pw1";
                    }

                    if (powerLevel >= 2) {
                        document.getElementById('pw2').className = "pw2";
                    }

                    if (powerLevel >= 3) {
                        document.getElementById('pw3').className = "pw3";
                    }

                    // Set wheelPower var used when spin button is clicked.
                    wheelPower = powerLevel;

                    // Light up the spin button by changing it's source image and adding a clickable class to it.
                    document.getElementById('spin_button').src = "./images/spin_on.png";
                    document.getElementById('spin_button').className = "./images/clickable";
                }
            }


            // -------------------------------------------------------
            // Click handler for spin button.
            // -------------------------------------------------------
            function startSpin()
            {
                // Ensure that spinning can't be clicked again while already running.
                if (wheelSpinning === false) {

                    theWheel.animation.spins = Math.floor(Math.random() * 11);
                    // Disable the spin button so can't click again while wheel is spinning.
                    document.getElementById('spin_button').src       = "./images/spin.png";
                    document.getElementById('spin_button').className = "";

                    // Begin the spin animation by calling startAnimation on the wheel object.
                    theWheel.startAnimation();

                    // Set to true so that power can't be changed and spin button re-enabled during
                    // the current animation. The user will have to reset before spinning again.
                    wheelSpinning = true;
                }
            }

            // -------------------------------------------------------
            // Function for reset button.
            // -------------------------------------------------------
            function resetWheel()
            {
                theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
                theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
                theWheel.draw();                // Call draw to render changes to the wheel.

                document.getElementById('pw1').className = "";  // Remove all colours from the power level indicators.
                document.getElementById('pw2').className = "";
                document.getElementById('pw3').className = "";

                wheelSpinning = false;          // Reset to false to power buttons and spin can be clicked again.
            }

            // -------------------------------------------------------
            // Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
            // -------------------------------------------------------
            function alertPrize(indicatedSegment)
            {
                var amt = parseInt(indicatedSegment.text);
                // Just alert to the user what happened.
                // In a real project probably want to do something more interesting than this with the result.
                if (indicatedSegment.text === 'NO MONEY') {
                    alert('Awwww, You didn\'t make any money, try again in 24 hours!');
                } else if (indicatedSegment.text === 'BANKRUPT') {
                    alert('Oh no, you have gone BANKRUPT!');
                } else {
                    if (window.XMLHttpRequest){
                        xmlhttp = new XMLHttpRequest();
                    }

                    else{
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }

                    var PageToSendTo = "./email/index.php?";
                    var MyVariable = amt;
                    var VariablePlaceholder = "amount=";
                    var UrlToSend = PageToSendTo + VariablePlaceholder + MyVariable;

                    xmlhttp.open("GET", UrlToSend, false);
                    xmlhttp.send();


                    alert("You have won RM" + indicatedSegment.text);
                    window.location.href='./email/index.php';
                }
            }
        </script>
    </body>
</html>
