
/* -----------------------------------------------------------------

	CO225 Lab03

	Author      : 	Jaliyagoda A.J.N.M. (E/15/140)
	LastUpdate  :	2018/12/02

----------------------------------------------------------------- */


class Main {

    public static void main(String[] args) {

        // Creating a and b random matrices
        int n = 600, m = 400, k = 600;

        // Generating random matrix of n x m with the range of 0-1000
        int[][] a = Matrix.randomMatrix(n, m, 1000);
        int[][] b = Matrix.randomMatrix(m, k, 1000);

        // Printing as the format of array define in Java
        //Matrix.printMatrixFormat(a, "a");
        //Matrix.printMatrixFormat(b, "b");

        // Print input matrices
        //Matrix.printMatrix(a, "A");
        //Matrix.printMatrix(b, "B");

        // -------------------------------------------------------------------------------------------------

        System.out.println("Matrix Multiplication without using Threads:");
        withoutThreads(a, b);

        // -------------------------------------------------------------------------------------------------

        int noOfThreads = 4;

        System.out.println("Matrix Multiplication with " + noOfThreads + " Threads:");
        withThreads(a, b, noOfThreads);

        // Threads are only effective if the matrix size exceeds 100x100.
        //
    }

    public static void withoutThreads(int[][] a, int[][] b) {
        ExecTimer.startNow();

        int[][] withoutThreads = Matrix.multiplyMatrix(a, b);
        //Matrix.printMatrix(withoutThreads); // Printing the result matrix
        ExecTimer.printTimeSpend();

    }

    public static void withThreads(int[][] a, int[][] b, int noOfThreads) {
        // Using Threads

        Thread[] tr = new Thread[noOfThreads];
        Matrix[] M = new Matrix[noOfThreads];

        int n = a.length, m = a[0].length;
        int i = 0;

        ExecTimer.startNow();

        for (i = 0; i < noOfThreads; i++) {
            // Divide the process into 'noOfThreads' parallel running threads

            if (i == noOfThreads - 1) {
                // Last thread may have few more rows if (n%noOfThreads !=0)
                M[i] = new Matrix(a, b, "thread" + i, i * (n / noOfThreads), (n - i * (n / noOfThreads)));
            } else {
                M[i] = new Matrix(a, b, "thread" + i, i * (n / noOfThreads), (n / noOfThreads));
            }

            tr[i] = new Thread(M[i], "thread" + i);
            tr[i].start();
        }

        try {
            // Wait until all the threads finish their calculations
            for (i = 0; i < noOfThreads; i++) {
                tr[i].join();
            }
            //Matrix.printMatrix(Matrix.c); // Printing the result matrix

        } catch (InterruptedException e) {
            System.out.println("Error occurred !");
            e.printStackTrace();
        }
        ExecTimer.printTimeSpend();
    }
}