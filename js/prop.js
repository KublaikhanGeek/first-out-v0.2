class Prop{
  constructor(x, y, w, h, src){
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.img = new Image();
    this.img.src = src;
  }

  draw() {
    ctx.globalCompositeOperation = 'destination-over';
    ctx.clearRect(this.x,this.y,this.w,this.h);
    ctx.drawImage(this.img, this.x, this.y, this.w, this.h);
  }

  reallocate(x_ratio, y_ratio) {
    ctx.clearRect(this.x,this.y,this.w,this.h);
    this.x *= x_ratio;
    this.y *= y_ratio;
    this.w *= x_ratio;
    this.h *= y_ratio;
  }
}
