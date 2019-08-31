import javax.swing.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class GameResults extends JPanel implements ActionListener {

    // Dimensions
    private static final int screenWidth = 240;
    private static final int screenHeight = 180;

    // GUI components
    private static JFrame frame;
    private JButton okBtn = new JButton();
    private JLabel label = new JLabel();

    public GameResults() {
        frame = new JFrame("Results");
        draw();
    }

    public void showMessage(String txt) {
        frame.setVisible(true);
        label.setText(txt);
    }

    public void actionPerformed(ActionEvent e) {
        frame.setVisible(false);
    }

    // Private methods
    private void draw() {

        frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        frame.setContentPane(this);

        okBtn.setText("Ok");
        okBtn.setBounds(screenWidth / 2 - 50, screenHeight - 80, 100, 30);
        okBtn.addActionListener(this);
        frame.add(okBtn);

        label.setText("");
        label.setBounds(screenWidth / 2 - 100, screenHeight - 150, 200, 30);
        frame.add(label);

        frame.setSize(screenWidth, screenHeight);
        frame.setResizable(false);
        frame.setLayout(null);
    }


}

