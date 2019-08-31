import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class View extends JPanel {

    // Dimensions
    private static final int areaWidth = 360;
    private static final int areaHeight = 360;
    private static final int screenWidth = 480;
    private static final int screenHeight = 480;

    private Controller c;

    // GUI components
    private static JFrame frame;

    JButton[] btn = new JButton[9];
    Color playerOneColor, playerTwoColor, defaultColor;


    public View(Controller c) {

        frame = new JFrame("Tic-Tac-Toe");
        this.c = c;

        playerOneColor = new Color(212, 38, 34);
        playerTwoColor = new Color(68, 168, 22);
        defaultColor = new Color(199, 199, 199);
    }

    // Public methods
    public void begin() {

        draw();
        frame.setVisible(true);

    }

    public void setPlayerColor(JButton b, Color c) {
        b.setBackground(c);
    }

    public void showMessage(String msg) {
        JOptionPane.showMessageDialog(this, msg);
    }

    public void resetView() {
        for (int i = 0; i < 9; i++) {
            btn[i].setBackground(defaultColor);
            btn[i].setText("");
        }
    }

    // Private methods
    private void draw() {

        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setContentPane(this);

        int x, y;
        int w = (areaWidth / 3);
        int h = (areaHeight / 3);

        Font f = new Font("Verdana", Font.PLAIN, 32);

        for (int i = 0; i < 9; i++) {
            btn[i] = new JButton("");
            x = (i % 3) * (areaHeight / 3) + (screenHeight - areaHeight) / 2;
            y = (i / 3) * (areaWidth / 3) + (screenWidth - areaWidth) / 2;

            btn[i].setBounds(x, y, h, w);
            btn[i].addActionListener(c);
            btn[i].setFont(f);
            btn[i].setBackground(defaultColor);

            frame.add(btn[i]);
        }


        frame.setSize(screenWidth, screenHeight);
        frame.setResizable(false);
        frame.setLayout(null);
        //frame.setVisible(true);
    }



}
