class HandlerFirma {
  canvas = null;
  ctx = null;
  // Ancho y altura adicional
  WIDTH_EXTRA = 50;
  HEIGHT_EXTRA = 200;
  prevMousePoint;
  snapshot;
  isDrawing = false;
  // Herramienta lápiz
  selectedTool = "brush";
  // Ancho de la linea
  brushSize = 4;
  // Color de firma
  selectedColor = "#0000F1";

  setCanvasBackground() {
    this.ctx.fillStyle = "#fff";
    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
    this.ctx.fillStyle = this.selectedColor;
  }
  constructor() {
    this.canvas = document.querySelector("canvas");
    this.ctx = this.canvas.getContext("2d");
    this.ctx.lineCap = "round";
    this.setCanvasBackground();
    this.canvas.addEventListener("mousedown", this.startDraw);
    this.canvas.addEventListener("touchstart", this.startDraw);
    this.canvas.addEventListener("mousemove", this.drawing);
    this.canvas.addEventListener("touchmove", this.drawing);
    this.canvas.addEventListener("mouseup", () => this.isDrawing = false);
    this.canvas.addEventListener("mouseleave", () => this.isDrawing = false);
    this.canvas.addEventListener("touchend", () => this.isDrawing = false);
  }

  currMousePoint = e => {
    // Inicializando las coordenadas
    let x = "ontouchstart" in window ? e.touches?.[0].clientX : e.offsetX;
    let y = "ontouchstart" in window ? e.touches?.[0].clientY : e.offsetY;
    // Verificando el evento touch para configurar las coordenadas
    if ("ontouchstart" in window) {
      x = x - this.WIDTH_EXTRA;
      y = y - this.HEIGHT_EXTRA;
    }
    // alert(`comp: ${x} - ${y}`)
    return { x, y };
  }

  startDraw = e => {
    this.isDrawing = true;
    this.ctx.beginPath();
    this.prevMousePoint = this.currMousePoint(e);
    this.ctx.lineWidth = this.brushSize;
    this.ctx.strokeStyle = this.selectedColor;
    this.ctx.fillStyle = this.selectedColor;
    this.snapshot = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
  }

  drawing = e => {
    if (!this.isDrawing) return;

    this.ctx.putImageData(this.snapshot, 0, 0);
    let position = this.currMousePoint(e);
    if (this.selectedTool === "brush") {
      this.ctx.strokeStyle = this.selectedColor;
      this.ctx.lineTo(position.x, position.y);
      this.ctx.stroke();
    }
  }

  clean_draw() {
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.setCanvasBackground();
  }

  save_draw = () => { return this.canvas.toDataURL(); }
}