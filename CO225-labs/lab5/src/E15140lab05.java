/* -----------------------------------------------------------------
	CO225 Lab05

	Author      : 	Jaliyagoda A.J.N.M. (E/15/140)
	LastUpdate  :	2018/12/27
----------------------------------------------------------------- */

public class E15140lab05 {
    public static void main(String[] agrs) {

        Model m = new Model();
        Controller c = new Controller();
        View v = new View(c);

        c.start(v, m);
    }
}