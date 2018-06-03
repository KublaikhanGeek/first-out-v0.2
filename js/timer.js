class Timer{
  constructor(duration){
    this.x = canvas.width*0.482;
    this.y = canvas.height*0.066;
    this.duration = duration;
    this.font = 30;
    this.pause = false;
  }
  reallocate(x_ratio, y_ratio){
    this.x = this.x * x_ratio;
    this.y = this.y * y_ratio;
  }

  pauseTime(){
    this.pause = true;
  }

  startTimer(){
    var timer = this.duration + 4, minutes, seconds;
    var self = this;

    var tik = setInterval(function(){
      if(self.pause){
        clearInterval(tik);
      }
      minutes = parseInt(timer / 60, 10);
      seconds = parseInt(timer % 60, 10);

      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      var text = minutes + ":" + seconds;

      if(timer == self.duration + 1){
        text = "START";
        blocked = false;
      }else if(timer > self.duration){
        text = (timer - self.duration - 1) + " !!";
      }

      self.draw(text);
      if (--timer < 0) {
        clearInterval(tik);
        result();
      }
    }, 1000);
  }

  draw(text){
    ctx.font = this.font + "px Arial";
    var text_width = ctx.measureText(text).width + 30;
    var text_height = this.font;
    var gradient= ctx.createLinearGradient(0,0,canvas.width,0);
    gradient.addColorStop("1.0","white");
    //gradient.addColorStop("0.52","red");
    //gradient.addColorStop("0.54","yellow");
    ctx.strokeStyle= gradient;
    //text += "ssssssssssssssssssssssss";
    ctx.clearRect(this.x, this.y - text_height, text_width, text_height + 10);
    ctx.strokeText(text, this.x, this.y);
  }
}
