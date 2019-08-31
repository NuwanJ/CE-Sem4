

class Model {

    // column, row
    private int[][] gameField;
    private int[] playerWeight = {0, 1, 5};

    public Model(){
        // Initialize default values
        gameField = new int[3][3];
    }

    public int setPlay(int player, int index) {
        int row = index / 3;
        int col = index % 3;

        gameField[col][row] = playerWeight[player];   // 1 for player1, 5 for player2

        return controlLogic();
    }

    private int controlLogic() {

        int s;

        // Check each row
        for (int i = 0; i < 3; i++) {
            s = rowSum(gameField, i);
            if (s == 3) {
                // player1 wins
                return 1;
            } else if (s == 15) {
                // player2 wins
                return 2;
            }
        }

        // Check each column
        for (int i = 0; i < 3; i++) {
            s = colSum(gameField, i);
            if (s == 3) {
                // player1 wins
                return 1;
            } else if (s == 15) {
                // player2 wins
                return 2;
            }
        }

        // Check main diagonal
        s = diagSum(gameField, true);
        if (s == 3) {
            // Player1 wins
            return 1;
        } else if (s == 15) {
            // Player2 wins
            return 2;
        }

        // Check second diagonal
        s = diagSum(gameField, false);
        if (s == 3) {
            // Player1 wins
            return 1;
        } else if (s == 15) {
            // Player2 wins
            return 2;
        }

        if (isAllMarked(gameField)) return -1;

        return 0;
    }

    public void resetGame() {
        for (int i = 0; i < 3; i++) {
            for (int j = 0; j < 3; j++) {
                gameField[i][j] = 0;
            }
        }
    }

    // Private Methods

    private boolean isAllMarked(int[][] ar) {
        for (int i = 0; i < 3; i++) {
            for (int j = 0; j < 3; j++) {
                if (ar[j][i] == 0) return false;
            }
        }

        return true;
    }

    private int rowSum(int[][] ar, int row) {
        return (ar[0][row] + ar[1][row] + ar[2][row]);
    }
    private int colSum(int[][] ar, int col) {
        return (ar[col][0] + ar[col][1] + ar[col][2]);
    }

    private int diagSum(int[][] ar, boolean type) {
        if (type) {
            // main diagonal
            return (ar[0][0] + ar[1][1] + ar[2][2]);
        } else {
            return (ar[2][0] + ar[1][1] + ar[0][2]);
        }
    }

    // Debug Methods
    private void printField() {

        System.out.println("----------------------");
        for (int i = 0; i < 3; i++) {
            for (int j = 0; j < 3; j++) {
                System.out.print(gameField[j][i] + " ");
            }
            System.out.println();
        }
        System.out.println();
    }
}
