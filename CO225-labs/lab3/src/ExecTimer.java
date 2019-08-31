public class ExecTimer {

    private static long start, end, duration;

    public static void startNow() {
        start = System.nanoTime();
    }

    public static long timeSpend() {
        end = System.nanoTime();
        duration = (end - start) / 1000;

        return duration;
    }

    public static void printTimeSpend() {

        long time = timeSpend();

        if (time > 1000000) {
            // Time in seconds
            System.out.println("\nExecution Time: " + timeSpend() / 1000000 + "s\n");

        } else if (time > 1000) {
            // Time in mili seconds
            System.out.println("\nExecution Time: " + timeSpend() / 1000 + "ms\n");

        } else {
            // Time in micro seconds
            System.out.println("\nExecution Time: " + timeSpend() + "us\n");
        }
        startNow(); // reset timer
    }

}
