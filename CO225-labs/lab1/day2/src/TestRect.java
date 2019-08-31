
public class TestRect{

    public static void main(String args[]){

        
        //Rect r = new Rect();
        //Rect r = new Rect(5, (double)10); // (l, c)
        Rect r = new Rect(5, 10);       // (l, w)
    
        System.out.println("Area: " + r.getArea());
        System.out.println("Color: " + r.getColor());
        
        System.out.println("\n\nStatic:\nMul");
        
        float a = MathOp.mul((float)10.45,(float)1.25);
        System.out.println(a);
    }
}

class Rect{

    // Rect class attributes
    private int len, wid, color;

    // -- Constructor -------------------------------------
    
    Rect(){
        // Add a unit rectangle
        this.len = 1;
        this.wid = 1;    
    }
    
    
    Rect(int l){
        // Add a square
        this.len = l;
        this.wid = l;    
    }
    
    // Method overloading 1
    Rect(int l, int w){
        this.len =l;
        this.wid = w;
        this.color = 0;
    }
    
    // Method overloading 2
    Rect(int l, double c){
        this.len =l;
        this.wid = l;
        this.color = (int)c;
    }
    
    // --- Class Methods ----------------------------
    public void setData(int l, int w){
        this.len =l;
        this.wid = w;
    }
    
    public int getArea(){
        return this.len * this.wid;
    }
    
    public int getColor(){
        return this.color;
    }
    
}



class MathOp{
    
    static float mul(float x, float y){
        return x*y;
    }
    
    
    
    
    
}