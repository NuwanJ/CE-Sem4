import java.util.Random;

public class Matrix extends Thread {

    // Matrices
    private static int[][] a;
    private static int[][] b;
    public static int[][] c;

    // Objects for threads
    private Thread t;
    private String threadName;

    // Variables for segment by segment calculations
    private int from, count;

    // ---- Constructors --------------------------------------------------------------------

    public Matrix(int[][] a, int[][] b) {
        this.a = a;
        this.b = b;
        this.c = new int[a.length][b[0].length];
    }

    public Matrix(int[][] a, int[][] b, String tName) {
        this(a, b);
        this.threadName = tName;
    }

    public Matrix(int[][] a, int[][] b, String tName, int from, int count) {
        this(a, b, tName);

        this.from = from;
        this.count = count;
    }

    // ---- Utility Functions -------------------------------------------------------------

    // Generate a random matrix
    public static int[][] randomMatrix(int h, int w) {
        return randomMatrix(w, h, 5);
    }
    public static int[][] randomMatrix(int h, int w, int bound) {
        Random r = new Random();
        int[][] m = new int[h][w];

        for (int i = 0; i < h; i++) {
            for (int j = 0; j < w; j++)
                m[i][j] = r.nextInt(bound);
        }

        return m;
    }

    // Print the given matrix
    public static void printMatrix(int[][] a) {

        for (int i = 0; i < a.length; i++) {
            for (int j = 0; j < a[i].length; j++)
                System.out.print(a[i][j] + " ");
            System.out.println();
        }
        System.out.println();
    }
    public static void printMatrix(int[][] a, String mName) {
        // Printing the matrix
        System.out.println(mName + "=");
        printMatrix(a);
    }

    // Print the given matrix in format of array define in Java
    public static void printMatrixFormat(int[][] a, String mName) {

        System.out.print( "int[][] " + mName  + " = {");
        for (int i = 0; i < a.length; i++) {
            System.out.print("{");

            for (int j = 0; j < a[i].length; j++) {
                System.out.print(a[i][j]);

                if (j < a[i].length - 1) {
                    System.out.print(",");
                }
            }

            System.out.print("}");
            if (i < a.length - 1) {
                System.out.print(",");
            }
        }
        System.out.println("}");
    }


    // ---- General Purpose Functions -----------------------------------------------------

    public static int[][] multiplyMatrix(int[][] a, int[][] b) {
        // Usual Array Multiplication

        int x = a.length;
        int y = b[0].length;

        int z1 = a[0].length;
        int z2 = b.length;

        if (z1 != z2) {
            System.out.println("Cann not multiply");
            return null;
        }

        int[][] d = new int[x][y];
        int i, j, k, s;

        for (i = 0; i < x; i++) {
            for (j = 0; j < y; j++) {
                for (s = 0, k = 0; k < z1; k++) s += a[i][k] * b[k][j];
                d[i][j] = s;
            }
        }

        return d;
    }


    public static int[][] multiplyBySegments(int[][] a, int[][] b, int from, int count) {
        // Multiply using threads

        int z1 = a[0].length, z2 = b.length;

        if (z1 != z2) {
            System.out.println("Can not multiply");
            return null;
        }

        int i, aLen = a[0].length, bLen = b.length;
        int[][] d = new int[a.length][b[0].length];
        for (i = from; i < (from + count); i++) {
            //System.out.println(" c[i] = c[" + i + "]");
            d[i] = multiplyARow(a[i], a[0].length, b, b[0].length);
        }

        return d;
    }
    private static int[] multiplyARow(int[] row, int rowLen, int[][] b, int bLen) {
        int i, k;

        int[] ans = new int[b[0].length];

        for (k = 0; k < b[0].length; k++) {
            for (i = 0; i < rowLen; i++) {
                ans[k] += row[i] * b[i][k];
                //System.out.println("ans[" + k + "] += row[" + i + "] * b[" + i + "][" + k + "];");
                //System.out.println("\t\t" + ans[k] + " = " + row[i] + " * " + b[i][k]);

            }
            //System.out.println("");
        }
        return ans;
    }

    // ---- Thread Related Methods --------------------------------------------------------

    @Override
    public void run() {
        System.out.println(threadName + " running from: a.row" + from + " to: a.row" + (int) (from + count));
        multiplyBySegments(a, b, from, count);
    }

    @Override
    public void start() {
        if (t == null) {
            t = new Thread(this, threadName);
            t.start();
        }
    }
}