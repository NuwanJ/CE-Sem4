/*
    CO225 - Programming Methodology | Lab03

    Author      : Jaliyagoda A.J.N.M. (E/15/140)
    Last update : 2018-11-01

 */

public class E15140Ball {
    public static void main(String[] args) {

        Ball b1 = new Ball(0.0, 1.0, 10.0, 45.0);
        Ball.updateTime(5);

        Ball b2 = new Ball(0.0, 1.0, 20.0, 45.0);
        Ball.updateTime(5);

        if (b1.willCollide(b2)) {
            System.out.println("B1 and B2 will collide");
        } else {
            System.out.println("B1 and B2 won’t collide");
        }

        // Debugging purpose only
        // b1.printPosition();
        // b2.printPosition();

        Ball b3 = new Ball(-10.0, 5.0, 3.0, 30.0);
        Ball.updateTime(20);

        if (b2.willCollide(b3)) {
            System.out.println("B2 and B3 will collide");
        } else {
            System.out.println("B2 and B3 won’t collide");
        }
    }
}

class Ball {

    private double x;
    private double y;
    private double speed;
    private double angle;
    private double createdTime;
    private double lastUpdated;

    static double globalTime;

    // -- Constructor ----------------------------------------------------------------

    Ball(double x, double y, double speed, double angleOfSpeedWithX) {
        this.x = x;
        this.y = y;
        this.speed = speed;
        this.angle = angleOfSpeedWithX;
        this.createdTime = globalTime;  // only for reference
        this.lastUpdated = globalTime; 
    }

    // -- Public methods ----------------------------------------------------------------

    public double getX() {
        return this.x;
    }

    public double getY() {
        return this.y;
    }

    public double getTime() {
        return globalTime;
    }

    public void printPosition() {
        System.out.println("x: " + this.x + " y: " + this.y + " time: " + globalTime + " lastUpdate: " + this.lastUpdated);
    }

    public boolean willCollide(Ball b1) {

        // Need to update the position of the ball before collide check.
        this.updatePosition();
        b1.updatePosition();

        return (this.x == b1.getX() && this.y == b1.getY());
    }

    public void updatePosition() {

        double dT = globalTime - this.lastUpdated;

        this.x += (this.speed * dT) * Math.cos(Math.toRadians(this.angle));
        this.y += (this.speed * dT) * Math.sin(Math.toRadians(this.angle));

        this.lastUpdated = globalTime;
    }
}


