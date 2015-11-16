//PRocess to change the colors of each pin
//Variables to store each process
selectImg = '';
canvas = document.createElement("canvas");
ctx = canvas.getContext("2d");
originalPixels = null;
currentPixels = null;
color = '';
fullimg = '';
img = new Image();
img.src = "images/house_pin.png";
 
// Function for convert Hexdecimal code into RGB color
function HexToRGB(Hex){
 var Long = parseInt(Hex.replace(/^#/, ""), 16);
 return {
 R: (Long >>> 16) & 0xff,
 G: (Long >>> 8) & 0xff,
 B: Long & 0xff
 };
}
// Function to fill the color of generated image
function fillColor(path){
 color = path;
  
 if(!originalPixels) return; // Check if image has loaded
 var newColor = HexToRGB(color);
  
 for(var I = 0, L = originalPixels.data.length; I < L; I += 4){
  if(currentPixels.data[I + 3] > 0){
   currentPixels.data[I] = originalPixels.data[I] / 255 * newColor.R;
   currentPixels.data[I + 1] = originalPixels.data[I + 1] / 255 * newColor.G;
   currentPixels.data[I + 2] = originalPixels.data[I + 2] / 255 * newColor.B;
  }
 }
  
 ctx.putImageData(currentPixels, 0, 0);
 fullimg = canvas.toDataURL("image/house_pin.png");
}
 
// Function for draw a image
function overalayColor(color){
  //fullimg = document.getElementsByTagName('img')[0];
  selectImg = img;
  //alert(img.src);
  //alert(img.src);
  canvas.width = selectImg.width;
  canvas.height = selectImg.height;
 
  ctx.drawImage(selectImg, 0, 0, selectImg.naturalWidth, selectImg.naturalHeight, 0, 0, selectImg.width, selectImg.height);
  originalPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);
  currentPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);
   
  selectImg.onload = null;
  fillColor(color);
}
//End of the color process