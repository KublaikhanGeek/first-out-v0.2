//
// Core Javascript File that Runs All Games.
//
// NOTE: The following Notation is needed in ALL Javascript files
//       that run Jquery commands:
//       $(function() {
//         ...
//       });
//

//ALERT: You have to put global variables outside of Jquery Tags for them to work.

/* Cool Code to be tested */
/*
$('#canvas_set').fadeOut(2000);
*/

// The Main Canvas character and 2D Context variable
var canvas, ctx;

// The "Cliff" character character.
var cliff;

// The Sets/Backgrounds of the show.
var sets;

//the lake
var lake;

var door1, door2;

//20sec timer
var timer;

//block any action before the timer starts
var blocked = true;

// Boundary Restrictions
var boundaries = [];

//last time jump key been pressed
lastJump = new Date();

//Result Alert
resultAlert = 0;

//resize things when window has been resized
function resize_canvas(){
    canvas = document.getElementById("canvas_set");
    var x_ratio = window.innerWidth/canvas.width;
    var y_ratio = window.innerHeight/canvas.height;
    if (canvas.width  < window.innerWidth || canvas.width  > window.innerWidth ){
        canvas.width  = window.innerWidth;
    }
    if (canvas.height < window.innerHeight || canvas.height > window.innerHeight){
        canvas.height = window.innerHeight;
    }

    //resize the boundary
    boundaries['left'] = new Boundary(-cliff.w/6,1,-cliff.w/6,canvas.height);
    boundaries['right'] = new Boundary(canvas.width+cliff.w/6,1,canvas.width+cliff.w/6,canvas.height);
    boundaries['lake'] = new Boundary(canvas.width*0.42, canvas.height*0.53, canvas.width*0.61, canvas.height*0.53);
    boundaries['left_lake']= new Boundary(canvas.width*0.42,canvas.height*0.54,canvas.width*0.42,canvas.height);
    boundaries['right_lake']= new Boundary(canvas.width*0.61,canvas.height*0.54,canvas.width*0.61,canvas.height);

    //reallocate the character and the lake
    cliff.reallocate(x_ratio, y_ratio);
    //reallocate the timer
    timer.reallocate(x_ratio, y_ratio);
}


//The first Scene
function firstScene() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  // SMOOTH LINES - ON/OFF
  ctx.mozImageSmoothingEnabled = false;

  //creating cliff, lake and timer
  cliff = new Character("cliff", -10, canvas.height*0.361, 115, canvas.height*0.17, 'right-running-8');
  lake = new Prop(canvas.width* 0.42, canvas.height*0.743, canvas.width*0.189, canvas.height*0.26, './images/props/lake.png');
  door1 = new Prop(-20, canvas.height* 0.223, canvas.width* 0.15, canvas.height* 0.35, './images/props/door.png');
  door2 = new Prop(canvas.width* 0.915, canvas.height* 0.223, canvas.width* 0.15, canvas.height* 0.35, './images/props/door.png');
  timer = new Timer(20);

  resize_canvas();
  cliff.draw();
  timer.startTimer();
}

function result(){
  if(!resultAlert) {
    if(cliff.x + cliff.w < canvas.width - 10){
      alert("You lost!");
    }else{
      alert("You won!");
    }
  }
  resultAlert = 1;
}

//setUp
$(document).ready(function() {

  // FORMAT is MP3 for now on.
  // Review Sound File Below (could be too funny with dialogue)
  // sound_file = './sounds/2009-dietmarhess-excl-show_beats_01_proud_music_preview';
  sound_file = './sounds/soundimage-cat-burglars';
  var episode1Sound = new buzz.sound( sound_file, {
    formats: [ "ogg", "mp3", "aac", "wav" ],
    preload: true,
    autoplay: true,
    loop: true,
    volume: 10
  });

  /* Create the Canvas character */
  canvas = document.getElementById('canvas_set');
  /* Set the 2D Context */
  ctx = canvas.getContext("2d");

  /* SCALE the Context
  The 0.04 was added as they Eyes of the characters were too small with
  scale(0.8,0.8);
  */
  ctx.scale(0.84,0.84);

  /* Sets/Backgrounds of Show */
  sets = [];
  sets[0] = './images/sets/hospital-private-room.png';

  $('#canvas_set').css('background-image', 'url("' + sets[0] + '")');
  $('#canvas_set').css({'background-size': "100% 100%", "background-repeat":"no-repeat"});
  firstScene();


  /* Volume Control */
  $('#volume_plus').click(function(){
    // Make it gradual plus
    episode1Sound.setVolume(100);
  });

  $('#volume_minus').click(function(){
    // Make it gradual minus
    episode1Sound.setVolume(10);
  });
});


//auxallary function to tell you the coordinates
function doMouseDown(event){
  x = event.pageX - canvas.offsetLeft;
  y = event.pageY - canvas.offsetTop;

  // NOTE: The line below alerts the co-ordinates of the canvas tag.
  alert('x:' + x + ' y:' + y);
}

//Press Keys
$(document).keydown(function(event) {
    if(!blocked){
      switch(event.keyCode) {
        case 32: // Space key - Char1 jump
          var currentTime = new Date();
          if((currentTime.getTime() - lastJump) > 350 && !cliff.falling){
            new Audio("./sounds/jump.mp3").play();//play the jump sound
            cliff.jump();
            lastJump = currentTime.getTime();
          }
          break;
        case 37: // left arrow - Char1 left
          cliff.move("left");
          break;
        case 39: // right arrow - Char1 right
          cliff.move("right");
          break;
        default: return; // exit this handler for other keys
      }
    }
    event.preventDefault();// prevent the default action (scroll / move caret)
});
