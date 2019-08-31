import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class Controller implements ActionListener {

    private View v;
    private GameResults v2;

    private Model m;

    int player = 1;

    public Controller() {
        // Nothing to do in herre
    }

    public void start(View view, Model model) {
        v = view;
        m = model;

        v.begin();
        v2 = new GameResults();
    }

    public void actionPerformed(ActionEvent e) {

        for (int i = 0; i < 9; i++) {
            if (e.getSource() == v.btn[i]) {

                if (v.btn[i].getText().compareTo("") == 0) {
                    if (player == 1) {
                        v.setPlayerColor(v.btn[i], v.playerOneColor);
                    } else {
                        v.setPlayerColor(v.btn[i], v.playerTwoColor);
                    }

                    v.btn[i].setText(Integer.toString(player));

                    int res = m.setPlay(player, i);

                    if (res == 1) {
                        //debug("Player 1 wins !!!");
                        //v.showMessage("Player 1 wins !!!");        // message box
                        v2.showMessage("Player 1 wins !!!");     // separate window

                    } else if (res == 2) {
                        //debug("Player 2 wins !!!");
                        //v.showMessage("Player 2 wins !!!");        // message box
                        v2.showMessage("Player 2 wins !!!");     // separate window
                    } else if (res == -1) {
                        //debug("Game is draw !!!");
                        //v.showMessage("Game is draw !!!");        // message box
                        v2.showMessage("Game is draw !!!");     // separate window
                    }

                    // Reset the game if it is end
                    if (res == 0) {
                        player = nextPlayer();
                    } else {
                        reset();
                    }

                }
            }

        }
    }

    private int nextPlayer() {
        return 1 + (2 - player);
    }

    private void reset() {
        v.resetView();
        m.resetGame();
        player = 1;
    }

    private void onConfirm() {

    }

    private void debug(String s) {
        System.out.println(s);
    }
}
