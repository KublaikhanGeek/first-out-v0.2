class Character{
  constructor(name, x, y, w, h, image_state){
    this.name = name;
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.falling = false;
    this.moving = false;
    this.jumping = false;
    this.clothing = 'default';
    this.image_state = image_state;
    this.img = new Image();
    this.img.src = './images/chars/' + this.name + '/' + this.clothing + '/' + this.image_state + '.png';
  }

  //function to change character image source
  changeImgSrc() {
    this.img.src = './images/chars/' + this.name + '/' + this.clothing + '/' + this.image_state + '.png';
  }

  check(){
    if(cliff.x + cliff.w >= canvas.width - 10){
      alert("You won!");
    }
  }
  //Draw the character Imgae
  draw() {
    // ctx.beginPath();
    // ctx.arc(this.x + this.w/2,this.y + this.h/2, this.h/2,0,2*Math.PI);
    // ctx.stroke();
    lake.draw();
    door1.draw();
    door2.draw();
    this.changeImgSrc();
    ctx.drawImage(this.img, this.x, this.y, this.w, this.h);
  }

  //function to resize character
  reallocate(x_ratio, y_ratio) {
    ctx.clearRect(this.x,this.y,this.w,this.h);
    this.y = this.y * y_ratio;
    this.x = this.x * x_ratio;
    this.h = canvas.height*0.17;
    lake.reallocate(x_ratio, y_ratio);
    door1.reallocate(x_ratio, y_ratio);
    door2.reallocate(x_ratio, y_ratio);
    this.draw(ctx);
  }


  //if character will meet the boundary
  circleLineIntersect(x1, y1, x2, y2, ver) {

    var cx = this.x + this.w/2;
    var cy = this.y + this.h/2
    var cr = this.h/2;
    var dx = parseFloat(x2 - x1);
    var dy = parseFloat(y2 - y1);
    var a = parseFloat(dx * dx + dy * dy);
    var b = parseFloat(2 * (dx * (x1 - cx) + dy * (y1 - cy)));
    var c = parseFloat(cx * cx + cy * cy);
    c += parseFloat(x1 * x1 + y1 * y1);
    c -= parseFloat(2 * (cx * x1 + cy * y1));
    c -= parseFloat(cr * cr);

    var bb4ac = parseFloat(b * b - 4 * a * c);

    if(bb4ac<0){
        return false;    // No collision
    }

    if((cx  < x1 || cx > x2) && ver == false){
      return false;
    }else if((cy < y1 || cy > y2) && ver == true){
      return false;
    }
    return true;      //Collision
  }

  //character collision detection with lines
  boundary_detection(boundary) {

    // Collision with vertical lines
    if(boundary.x1 == boundary.x2){
      if(this.circleLineIntersect(boundary.x1, boundary.y1, boundary.x2, boundary.y2, true)) {
        return true;
      }
    // Collision with horizontal lines
    }else if(boundary.y1 == boundary.y2){
      if(this.circleLineIntersect(boundary.x1, boundary.y1, boundary.x2, boundary.y2, false)) {
        return true;
      }
    }
    return false;
  }

  //detect collision with the boundaries: left and right walls and lake surface and walls
  collision(){
    var direction = this.image_state.split("-")[0];

    //boundary detection with walls
    if(this.boundary_detection(boundaries[direction])) {
      ctx.clearRect(this.x,this.y - 5,this.w,this.h + 10);
      this.image_state =  direction + "-running-8";
      this.draw();
      return true;
    }

    //boundary detection with the lake
    if(this.boundary_detection(boundaries['lake'])) {
      this.fall();
      return true;
    }

    if(this.boundary_detection(boundaries[direction + '_lake'])) {
      ctx.clearRect(this.x,this.y - 5,this.w,this.h + 10);
      this.image_state =  direction + "-running-8";
      this.draw();
      return true;
    }

    return false;
  }

  //falling into lake
  fall(){

    this.falling = true;
    var self = this;//use "self" to replace "this" inside the function

    //falling
    var falling = setInterval(function(){

      if(self.y >= canvas.height*0.6){
        clearInterval(falling);
      }//stop
      ctx.clearRect(self.x,self.y*0.7,self.w,self.h*1.3);
      self.y += 10;
      self.draw();
    },100);

    var drown_start = new Date().getTime();
    //inside the lake
    var drowning = setInterval(function(){
      //touch the top
      if(self.y < canvas.height*0.6){
        self.y += 5;
      }else if(self.y > canvas.height*0.65){
        self.y -= 5;
      }

      //touch the lake wall
      if(self.boundary_detection(boundaries['left_lake'])) {
        self.x += 5;
      }else if (self.boundary_detection(boundaries['right_lake'])){
        self.x -= 5;
      }

      //floating
      ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
      self.x += (Math.round(Math.random()) * 2 - 1);
      self.y += (Math.round(Math.random()) * 2 - 1);
      self.draw();
      if(new Date().getTime() - drown_start >= 2000){
        timer.pauseTime();
        alert("You lost!");
        clearInterval(drowning);
      }
    },100);
  }

  //move the character for 24px
  move(direction){
    //character's walking direction: -1 for left, 1 for right
    var opt;
    this.moving = true;
    if(direction == "left"){
      opt = -1;
    }else{
      opt = 1;
    }

    //7 movements
    var self = this;//use "self" to replace "this" inside the function
    var count = 2;
    var stopped = false;
    var walk = setInterval(
    function(){
      if(self.collision() && self.image_state.split("-")[0] == direction){
        clearInterval(walk);
        self.moving = false;
        stopped = true;
        //bump into wall and walk in the same direction as wall
      }
      if(!stopped){
        //ctx.fillRect(self.x,self.y,self.w,self.h);
        ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
        var width = opt * 5;

        self.x = self.x + width;
        self.image_state = direction + "-running-" + count;
        self.draw();
        count++;
        if(count > 8){
          self.moving = false;
          clearInterval(walk);
        }
        if(self.jumping){
          self.moving = false;
          self.jump_move(direction);
          clearInterval(walk);
        }
      }
    }, 50);
    this.check();
  }

  //make character jump for 60px
  jump() {
    // if character is within the lake, can't jump
    if(this.x - this.w/2 > boundaries['lake'].x1 && this.x + this.w/2 < boundaries['lake'].x2){
      return;
    }
    this.jumping = true;
    var self = this;//use "self" to replace "this" inside the function
    var count = 1;
    var up = -30; //up 30px
    var up_sum = 0;//up pixels so far

    var direction = this.image_state.split("-")[0];//current direction
    var op;//going left or right 50px
    if(direction == "left"){
      op = -50;
    }else{
      op = 50;
    }


    var count = 0;
    var jump = setInterval(
      function(){
            if(count == 7){
              self.jumping = false;
              clearInterval(jump);

            }else if(count == 4){
              up = - up;
            }
            ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
            self.y += up;
            up_sum += up;
            self.image_state = direction + "-running-8";
            self.draw();
            count ++;
            if(self.moving){
              ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
              self.jumping = false;
              self.y -= up_sum;
              self.draw();
              clearInterval(jump);
            }
      },50);
  }

  jump_move(direction) {
    if(this.x - this.w/2 > boundaries['lake'].x1 && this.x + this.w/2 < boundaries['lake'].x2){
      return;
    }
    var self = this;//use "self" to replace "this" inside the function
    var count = 1;
    var up = -30; //up 30px
    var status = 1; // current status: 1 for ongoing, -1 for bumpped into wall
    var up_sum = 0;//up pixels so far

    var op;//going left or right 50px
    if(direction == "left"){
      op = -10;
    }else{
      op = 10;
    }

  //start to jump 8 movements
    var jump = setInterval(
    function(){
      //bump into wall
      if(self.boundary_detection(boundaries[direction])){
        clearInterval(jump);//stop current jumping
        status = -1;

        //when in the middle of jumping
        if(up_sum != 0){
          ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
          self.y -= up_sum;
          self.image_state = direction + "-running-8";
          self.draw();
        }else{
          var i = 0;
          up = -10;
          var w_up= setInterval(
          function(){
            //console.log(i);
            if(i == 1){
              up = -up;
              clearInterval(w_up);
            }
            ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
            self.y += up;
            self.draw();
            i++;
          },100);
        }
      }

      //ongoing jumping
      if(status == 1){
        ctx.clearRect(self.x,self.y - 5,self.w,self.h + 10);
        self.y += up;
        up_sum += up;
        self.x += op;
        self.image_state = direction + "-running-" + count;
        self.draw();
        count++;
      }
      //going down
      if(count == 5){
        up = -up;
      }
      //last step
      if(count == 9){
        self.collision();
        clearInterval(jump);
      }
    }, 50);
  }
}
